"""
Re-map the TrueHelp template palette to the ISOC palette.

Every colour literal in the given CSS files whose hue falls in the purple / pink /
teal range is rotated to ISOC blue (hue 215), keeping its saturation and brightness,
so dark purple backgrounds become ISOC navy and lavender tints become cool blues.

Usage: python tools/recolor_theme.py public/assets/css/truehelp.css public/assets/css/truehelp-dark.css
Run it against fresh copies of the template CSS (it is not idempotent-safe on hand edits).
"""
import colorsys
import re
import sys

TARGET_HUE = 215 / 360


def shift(r, g, b):
    h, s, v = colorsys.rgb_to_hsv(r / 255, g / 255, b / 255)
    deg = h * 360
    if s < 0.06 or not (150 <= deg <= 350):
        return None
    r2, g2, b2 = colorsys.hsv_to_rgb(TARGET_HUE, min(s, 0.85), v)
    return round(r2 * 255), round(g2 * 255), round(b2 * 255)


def hex_sub(m):
    raw = m.group(1)
    full = ''.join(c * 2 for c in raw) if len(raw) == 3 else raw
    new = shift(*(int(full[i:i + 2], 16) for i in (0, 2, 4)))
    return m.group(0) if new is None else '#%02X%02X%02X' % new


def rgb_sub(m):
    new = shift(int(m.group(2)), int(m.group(3)), int(m.group(4)))
    if new is None:
        return m.group(0)
    return f'{m.group(1)}{new[0]}, {new[1]}, {new[2]}'


def triple_sub(m):  # "--x-rgb: 138, 101, 160;"
    new = shift(int(m.group(2)), int(m.group(3)), int(m.group(4)))
    return m.group(0) if new is None else f'{m.group(1)}{new[0]}, {new[1]}, {new[2]};'


for path in sys.argv[1:]:
    css = open(path, encoding='utf-8').read()
    css = re.sub(r'#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})\b', hex_sub, css)
    css = re.sub(r'(rgba?\(\s*)(\d+)\s*,\s*(\d+)\s*,\s*(\d+)', rgb_sub, css)
    css = re.sub(r'(-rgb:\s*)(\d+),\s*(\d+),\s*(\d+);', triple_sub, css)
    open(path, 'w', encoding='utf-8').write(css)
    print('recoloured', path)

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
$type = 'cidfont0';
$name = 'KozGoPro-Medium-Acro';
$displayname = 'Kozuka Gothic Pro (Japanese Sans-Serif)';
$desc = array(
	'Ascent' => 880,
	'Descent' => -120,
	'CapHeight' => 763,
	'Flags' => 4,
	'FontBBox' => '[-149 -374 1254 1008]',
	'ItalicAngle' => 0,
	'StemV' => 99,
	'Style' => '<< /Panose <0000020b0700000000000000> >>',
	'XHeight' => 549,
);
$cidinfo = array(
	'Registry' => 'Adobe',
	'Ordering' => 'Japan1',
	'Supplement' => '4',
);
$enc = 'UniJIS-UCS2-H';

// underline position, needs checking:
$up = -75;
$ut = 50;

$dw = 1000;
$cw = array(
	32 => 224, 33 => 266, 34 => 392, 35 => 551, 36 => 562, 37 => 883, 38 => 677, 39 => 213, 40 => 322, 41 => 322,
	42 => 470, 43 => 677, 44 => 247, 45 => 343, 46 => 245, 47 => 370, 48 => 562, 49 => 562, 50 => 562, 51 => 562,
	52 => 562, 53 => 562, 54 => 562, 55 => 562, 56 => 562, 57 => 562, 58 => 245, 59 => 247, 60 => 677, 61 => 677,
	62 => 677, 63 => 447, 64 => 808, 65 => 661, 66 => 602, 67 => 610, 68 => 708, 69 => 535, 70 => 528, 71 => 689,
	72 => 703, 73 => 275, 74 => 404, 75 => 602, 76 => 514, 77 => 871, 78 => 708, 79 => 727, 80 => 585, 81 => 727,
	82 => 595, 83 => 539, 84 => 541, 85 => 696, 86 => 619, 87 => 922, 88 => 612, 89 => 591, 90 => 584, 91 => 322,
	92 => 562, 93 => 322, 94 => 677, 95 => 568, 96 => 340, 97 => 532, 98 => 612, 99 => 475, 100 => 608, 101 => 543,
	102 => 332, 103 => 603, 104 => 601, 105 => 265, 106 => 276, 107 => 524, 108 => 264, 109 => 901, 110 => 601, 111 => 590,
	112 => 612, 113 => 607, 114 => 367, 115 => 433, 116 => 369, 117 => 597, 118 => 527, 119 => 800, 120 => 511, 121 => 518,
	122 => 468, 123 => 321, 124 => 273, 125 => 321, 126 => 341, 127 => 241, 128 => 362, 129 => 241, 130 => 273, 131 => 677,
	132 => 266, 133 => 562, 134 => 562, 135 => 456, 136 => 562, 137 => 571, 138 => 562, 139 => 416, 140 => 472, 141 => 283,
	142 => 283, 143 => 587, 144 => 588, 145 => 568, 146 => 545, 147 => 545, 148 => 247, 149 => 561, 150 => 330, 151 => 239,
	152 => 418, 153 => 416, 154 => 472, 155 => 1136, 156 => 1288, 157 => 447, 158 => 340, 159 => 340, 160 => 340, 161 => 340,
	162 => 340, 163 => 340, 164 => 455, 165 => 340, 166 => 340, 167 => 340, 168 => 340, 169 => 1136, 170 => 857, 171 => 384,
	172 => 519, 173 => 727, 174 => 952, 175 => 398, 176 => 834, 177 => 264, 178 => 275, 179 => 590, 180 => 918, 181 => 605,
	182 => 677, 183 => 769, 184 => 677, 185 => 473, 186 => 361, 187 => 677, 188 => 347, 189 => 340, 190 => 599, 191 => 284,
	192 => 845, 193 => 845, 194 => 845, 195 => 661, 196 => 661, 197 => 661, 198 => 661, 199 => 661, 200 => 661, 201 => 610,
	202 => 535, 203 => 535, 204 => 535, 205 => 535, 206 => 275, 207 => 275, 208 => 275, 209 => 275, 210 => 715, 211 => 708,
	212 => 727, 213 => 727, 214 => 727, 215 => 727, 216 => 727, 217 => 677, 218 => 696, 219 => 696, 220 => 696, 221 => 696,
	222 => 591, 223 => 584, 224 => 532, 225 => 532, 226 => 532, 227 => 532, 228 => 532, 229 => 532, 230 => 475, 231 => 543,
	232 => 543, 233 => 543, 234 => 543, 235 => 264, 236 => 264, 237 => 264, 238 => 264, 239 => 584, 240 => 601, 241 => 590,
	242 => 590, 243 => 590, 244 => 590, 245 => 590, 246 => 677, 247 => 597, 248 => 597, 249 => 597, 250 => 597, 251 => 518,
	252 => 612, 253 => 518, 254 => 539, 255 => 591, 256 => 584, 257 => 446, 258 => 433, 259 => 683, 260 => 468, 261 => 562,
);
$_cr = array(
	array(231, 632, 500), // half-width
	array(8718, 8718, 500),
	array(9738, 9757, 250), // quarter-width
	array(9758, 9778, 333), // third-width
	array(12063, 12087, 500)
);
foreach($_cr as $_r) {
	for($i = $_r[0]; $i <= $_r[1]; $i++) {
		$cw[$i+31] = $_r[2];
	}
}
?>

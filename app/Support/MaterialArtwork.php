<?php

namespace App\Support;

/**
 * Menghasilkan ilustrasi SVG gaya buku cerita Montessori yang ramah anak (non-anime)
 * untuk seluruh materi awal (Hewan, Warna, dan Benda).
 */
class MaterialArtwork
{
    public static function render(string $label, string $color, string $categorySlug): string
    {
        if ($categorySlug === 'warna') {
            return self::renderColorCard($label, $color);
        }

        $key = strtolower(trim($label));
        $body = self::getArtworkBody($key, $color);
        $backdrop = self::backdropForCategory($categorySlug);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="512" height="512" role="img" aria-label="{$label}">
  <rect width="512" height="512" rx="48" fill="{$backdrop['bg']}"/>
  <circle cx="256" cy="252" r="196" fill="{$backdrop['halo']}"/>
  <ellipse cx="256" cy="422" rx="142" ry="20" fill="{$backdrop['shadow']}"/>
  {$body}
</svg>
SVG;
    }

    /**
     * @return array{bg: string, halo: string, shadow: string}
     */
    private static function backdropForCategory(string $categorySlug): array
    {
        if ($categorySlug === 'hewan') {
            return [
                'bg' => '#FFFDF8',
                'halo' => '#EAF4FA',
                'shadow' => '#DCE8F0',
            ];
        }

        return [
            'bg' => '#FFFDF8',
            'halo' => '#FCEFC7',
            'shadow' => '#E9DEC2',
        ];
    }

    private static function renderColorCard(string $label, string $color): string
    {
        $key = strtolower(trim($label));
        $isWhite = $key === 'putih';
        $isDark = $key === 'hitam';
        $cardBg = $isWhite ? '#DCEBF7' : '#FFFDF8';
        $strokeColor = $isWhite ? '#94B8D4' : '#E2D9C8';
        $motif = self::colorMotif($key, $isDark);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="512" height="512" role="img" aria-label="Warna {$label}">
  <rect width="512" height="512" rx="48" fill="{$cardBg}"/>
  <circle cx="152" cy="116" r="22" fill="{$color}" opacity="0.22"/>
  <circle cx="386" cy="390" r="28" fill="{$color}" opacity="0.18"/>
  <ellipse cx="256" cy="426" rx="138" ry="18" fill="#D5DFE8" opacity="0.7"/>
  <path d="M256 76 C362 76 426 144 426 244 C426 350 350 410 256 410 C156 410 86 346 86 248 C86 146 154 76 256 76 Z" fill="{$color}" stroke="{$strokeColor}" stroke-width="10"/>
  <path d="M168 134 C194 108 232 100 264 104" fill="none" stroke="#FFFFFF" stroke-width="16" stroke-linecap="round" opacity="0.45"/>
  <circle cx="136" cy="164" r="10" fill="#FFFFFF" opacity="0.45"/>
  {$motif}
</svg>
SVG;
    }

    private static function colorMotif(string $key, bool $isDark): string
    {
        $accent = $isDark ? '#FFF8DB' : '#FFFFFF';
        $badgeBg = $isDark ? '#2E2E2E' : 'rgba(255,255,255,0.22)';

        return match ($key) {
            'merah' => <<<'SVG'
  <g transform="translate(206 184)">
    <path d="M50 24 C22 24 8 46 8 74 C8 108 30 130 50 130 C70 130 92 108 92 74 C92 46 78 24 50 24 Z" fill="#FFFFFF" opacity="0.92"/>
    <path d="M50 24 Q50 4 64 2" stroke="#FFFFFF" stroke-width="7" stroke-linecap="round" fill="none"/>
    <ellipse cx="64" cy="14" rx="14" ry="7" transform="rotate(-24 64 14)" fill="#FFFFFF"/>
  </g>
SVG,
            'biru' => <<<'SVG'
  <path d="M174 282 C154 282 140 268 140 250 C140 234 152 222 168 220 C176 196 198 182 224 182 C254 182 278 202 284 228 C290 226 298 226 306 228 C326 232 340 248 340 264 C340 278 328 282 312 282 Z" fill="#FFFFFF" opacity="0.94"/>
SVG,
            'kuning' => <<<'SVG'
  <g stroke="#B45309" stroke-width="10" stroke-linecap="round" fill="#FFFBEB">
    <circle cx="256" cy="244" r="46"/>
    <line x1="256" y1="164" x2="256" y2="182"/>
    <line x1="256" y1="306" x2="256" y2="324"/>
    <line x1="176" y1="244" x2="194" y2="244"/>
    <line x1="318" y1="244" x2="336" y2="244"/>
    <line x1="199" y1="187" x2="212" y2="200"/>
    <line x1="300" y1="288" x2="313" y2="301"/>
    <line x1="313" y1="187" x2="300" y2="200"/>
    <line x1="212" y1="288" x2="199" y2="301"/>
  </g>
SVG,
            'hijau' => <<<'SVG'
  <g transform="translate(186 176)">
    <path d="M126 16 C64 16 18 58 18 118 C18 130 20 138 22 142 C82 142 126 98 126 16 Z" fill="#FFFFFF" opacity="0.92"/>
    <path d="M14 148 Q68 100 108 40" stroke="#2E7D32" stroke-width="7" stroke-linecap="round" fill="none"/>
  </g>
SVG,
            'putih' => <<<'SVG'
  <path d="M174 282 C154 282 140 268 140 250 C140 234 152 222 168 220 C176 196 198 182 224 182 C254 182 278 202 284 228 C290 226 298 226 306 228 C326 232 340 248 340 264 C340 278 328 282 312 282 Z" fill="#EBF4FC" stroke="#7BA6C9" stroke-width="6"/>
SVG,
            'hitam' => <<<SVG
  <g fill="{$accent}">
    <path d="M278 182 C242 186 218 216 218 250 C218 288 248 318 286 318 C302 318 316 312 328 302 C296 304 268 278 268 244 C268 218 282 196 302 184 C294 182 286 182 278 182 Z"/>
    <polygon points="196,198 203,214 220,216 207,228 210,244 196,236 182,244 185,228 172,216 189,214"/>
  </g>
SVG,
            'oranye' => <<<'SVG'
  <g transform="translate(206 188)">
    <circle cx="50" cy="64" r="46" fill="#FFFFFF" opacity="0.94"/>
    <circle cx="50" cy="64" r="36" fill="none" stroke="#FB8C00" stroke-width="5"/>
    <line x1="50" y1="28" x2="50" y2="100" stroke="#FB8C00" stroke-width="5"/>
    <line x1="14" y1="64" x2="86" y2="64" stroke="#FB8C00" stroke-width="5"/>
    <ellipse cx="58" cy="10" rx="16" ry="8" transform="rotate(-18 58 10)" fill="#FFFFFF"/>
  </g>
SVG,
            'ungu' => <<<'SVG'
  <g fill="#FFFFFF" opacity="0.93">
    <circle cx="232" cy="212" r="22"/>
    <circle cx="276" cy="212" r="22"/>
    <circle cx="212" cy="248" r="22"/>
    <circle cx="254" cy="248" r="22"/>
    <circle cx="296" cy="248" r="22"/>
    <circle cx="234" cy="284" r="22"/>
    <circle cx="274" cy="284" r="22"/>
    <circle cx="254" cy="316" r="20"/>
    <path d="M254 188 Q256 164 274 158" stroke="#FFFFFF" stroke-width="8" stroke-linecap="round" fill="none"/>
  </g>
SVG,
            'merah muda' => <<<'SVG'
  <g fill="#FFFFFF" opacity="0.94">
    <circle cx="256" cy="194" r="28"/>
    <circle cx="304" cy="228" r="28"/>
    <circle cx="286" cy="284" r="28"/>
    <circle cx="226" cy="284" r="28"/>
    <circle cx="208" cy="228" r="28"/>
    <circle cx="256" cy="244" r="24" fill="#FDE047"/>
  </g>
SVG,
            default => <<<SVG
  <g transform="translate(206 184)" fill="{$badgeBg}">
    <path d="M18 68 C18 46 34 34 50 34 C66 34 82 46 82 68 C82 104 62 128 50 134 C38 128 18 104 18 68 Z" fill="#FFFFFF" opacity="0.92"/>
    <path d="M12 48 C12 28 28 20 50 20 C72 20 88 28 88 48 Z" fill="#D7CCC8"/>
    <rect x="44" y="4" width="12" height="18" rx="6" fill="#D7CCC8"/>
  </g>
SVG,
        };
    }

    private static function getArtworkBody(string $key, string $color): string
    {
        return match ($key) {
            'kucing' => <<<SVG
  <!-- Ekor bergaris -->
  <path d="M332 362 C398 358 416 286 382 252" fill="none" stroke="{$color}" stroke-width="30" stroke-linecap="round"/>
  <path d="M388 310 L402 296" stroke="#B6631F" stroke-width="10" stroke-linecap="round"/>
  <path d="M368 344 L384 334" stroke="#B6631F" stroke-width="10" stroke-linecap="round"/>
  <!-- Badan duduk -->
  <ellipse cx="256" cy="342" rx="88" ry="74" fill="{$color}"/>
  <ellipse cx="256" cy="354" rx="52" ry="52" fill="#FFF1DF"/>
  <!-- Kaki depan -->
  <rect x="206" y="346" width="36" height="70" rx="18" fill="{$color}"/>
  <rect x="270" y="346" width="36" height="70" rx="18" fill="{$color}"/>
  <ellipse cx="224" cy="408" rx="20" ry="12" fill="#FFFDF9"/>
  <ellipse cx="288" cy="408" rx="20" ry="12" fill="#FFFDF9"/>
  <!-- Telinga runcing -->
  <polygon points="154,198 168,108 232,162" fill="{$color}" stroke="{$color}" stroke-width="14" stroke-linejoin="round"/>
  <polygon points="358,198 344,108 280,162" fill="{$color}" stroke="{$color}" stroke-width="14" stroke-linejoin="round"/>
  <polygon points="168,186 178,128 218,162" fill="#F9A8B8"/>
  <polygon points="344,186 334,128 294,162" fill="#F9A8B8"/>
  <!-- Kepala -->
  <ellipse cx="256" cy="218" rx="108" ry="88" fill="{$color}"/>
  <!-- Garis dahi -->
  <path d="M256 132 L256 158 M234 138 L240 160 M278 138 L272 160" stroke="#B6631F" stroke-width="9" stroke-linecap="round"/>
  <!-- Pipi & moncong -->
  <ellipse cx="192" cy="240" rx="22" ry="14" fill="#F9A8B8" opacity="0.55"/>
  <ellipse cx="320" cy="240" rx="22" ry="14" fill="#F9A8B8" opacity="0.55"/>
  <ellipse cx="236" cy="248" rx="24" ry="18" fill="#FFF4E6"/>
  <ellipse cx="276" cy="248" rx="24" ry="18" fill="#FFF4E6"/>
  <!-- Mata ramah -->
  <circle cx="210" cy="208" r="16" fill="#1F2933"/>
  <circle cx="302" cy="208" r="16" fill="#1F2933"/>
  <circle cx="215" cy="202" r="5" fill="#FFFFFF"/>
  <circle cx="307" cy="202" r="5" fill="#FFFFFF"/>
  <!-- Hidung segitiga & mulut -->
  <polygon points="244,234 268,234 256,248" fill="#E56B8B"/>
  <path d="M256 248 L256 258 M238 262 Q248 272 256 258 Q264 272 274 262" fill="none" stroke="#5C3210" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
  <!-- Kumis -->
  <path d="M188 238 L128 228 M188 250 L126 254 M324 238 L384 228 M324 250 L386 254" stroke="#5C3210" stroke-width="5" stroke-linecap="round"/>
SVG,
            'anjing' => <<<SVG
  <!-- Ekor bergoyang -->
  <path d="M326 364 C382 356 410 314 390 278" fill="none" stroke="{$color}" stroke-width="28" stroke-linecap="round"/>
  <!-- Badan -->
  <ellipse cx="256" cy="346" rx="86" ry="70" fill="{$color}"/>
  <ellipse cx="256" cy="358" rx="46" ry="48" fill="#F4DFC8"/>
  <!-- Kalung merah lucu -->
  <path d="M184 294 Q256 322 328 294" fill="none" stroke="#D94848" stroke-width="18" stroke-linecap="round"/>
  <circle cx="256" cy="316" r="14" fill="#F6C445" stroke="#B45309" stroke-width="4"/>
  <!-- Kaki depan -->
  <rect x="206" y="346" width="38" height="72" rx="19" fill="{$color}"/>
  <rect x="268" y="346" width="38" height="72" rx="19" fill="{$color}"/>
  <ellipse cx="225" cy="410" rx="22" ry="12" fill="#F4DFC8"/>
  <ellipse cx="287" cy="410" rx="22" ry="12" fill="#F4DFC8"/>
  <!-- Telinga jatuh -->
  <ellipse cx="144" cy="222" rx="34" ry="74" transform="rotate(14 144 222)" fill="#6B4226"/>
  <ellipse cx="368" cy="222" rx="34" ry="74" transform="rotate(-14 368 222)" fill="#6B4226"/>
  <!-- Kepala -->
  <ellipse cx="256" cy="208" rx="100" ry="86" fill="{$color}"/>
  <!-- Bercak mata -->
  <circle cx="212" cy="196" r="30" fill="#7D4F2D"/>
  <!-- Moncong cream -->
  <ellipse cx="256" cy="246" rx="54" ry="38" fill="#F7E6D4"/>
  <!-- Mata -->
  <circle cx="212" cy="196" r="16" fill="#1F2933"/>
  <circle cx="300" cy="196" r="16" fill="#1F2933"/>
  <circle cx="217" cy="190" r="5" fill="#FFFFFF"/>
  <circle cx="305" cy="190" r="5" fill="#FFFFFF"/>
  <!-- Lidah gembira -->
  <path d="M242 266 L242 282 C242 292 270 292 270 282 L270 266 Z" fill="#F472B6"/>
  <!-- Hidung besar membulat -->
  <ellipse cx="256" cy="234" rx="22" ry="16" fill="#2B1D14"/>
  <ellipse cx="250" cy="228" rx="7" ry="4" fill="#FFFFFF" opacity="0.5"/>
  <path d="M256 250 L256 260 M236 262 Q246 272 256 260 Q266 272 276 262" fill="none" stroke="#4A2D18" stroke-width="6" stroke-linecap="round"/>
SVG,
            'ayam' => <<<SVG
  <!-- Jengger merah di atas kepala -->
  <circle cx="228" cy="108" r="24" fill="#E53935"/>
  <circle cx="256" cy="96" r="28" fill="#E53935"/>
  <circle cx="286" cy="112" r="22" fill="#E53935"/>
  <!-- Kaki kuning -->
  <path d="M226 376 L226 414 M226 414 L208 420 M226 414 L226 424 M226 414 L244 420" stroke="#F59E0B" stroke-width="10" stroke-linecap="round"/>
  <path d="M286 376 L286 414 M286 414 L268 420 M286 414 L286 424 M286 414 L304 420" stroke="#F59E0B" stroke-width="10" stroke-linecap="round"/>
  <!-- Ekor bulu melengkung -->
  <path d="M352 252 C398 214 426 238 408 286 C428 280 434 314 398 328 Z" fill="#B91C1C"/>
  <!-- Badan gemuk -->
  <ellipse cx="268" cy="296" rx="116" ry="96" fill="{$color}"/>
  <ellipse cx="264" cy="310" rx="84" ry="70" fill="#FDE6D8"/>
  <!-- Kepala & leher menyatu -->
  <circle cx="242" cy="186" r="72" fill="#FFF3EB"/>
  <!-- Sayap -->
  <path d="M236 270 C298 258 346 292 330 338 C294 358 242 340 230 296 Z" fill="#C62828"/>
  <!-- Pial merah -->
  <ellipse cx="222" cy="236" rx="16" ry="22" fill="#E53935"/>
  <!-- Paruh kuning -->
  <polygon points="154,192 198,176 198,210" fill="#FBBF24"/>
  <!-- Mata -->
  <circle cx="226" cy="176" r="14" fill="#1F2933"/>
  <circle cx="230" cy="171" r="4" fill="#FFFFFF"/>
  <!-- Pipi -->
  <circle cx="256" cy="200" r="15" fill="#FCA5A5" opacity="0.7"/>
SVG,
            'bebek' => <<<SVG
  <!-- Air kolam tenang -->
  <ellipse cx="256" cy="392" rx="176" ry="34" fill="#BBE1F8"/>
  <path d="M132 392 Q184 378 236 392 T340 392" fill="none" stroke="#FFFFFF" stroke-width="6" stroke-linecap="round"/>
  <!-- Badan bebek kuning mengapung -->
  <path d="M148 312 C148 264 202 246 276 254 C338 260 392 238 402 254 C412 288 378 368 276 374 C182 380 148 352 148 312 Z" fill="{$color}"/>
  <!-- Jambul lucu -->
  <path d="M206 122 C202 96 224 94 226 116 C236 96 252 106 240 126" fill="none" stroke="#EAB308" stroke-width="12" stroke-linecap="round"/>
  <!-- Kepala -->
  <circle cx="212" cy="196" r="76" fill="{$color}"/>
  <!-- Sayap -->
  <path d="M242 292 C304 274 356 296 346 334 C312 352 256 348 238 316 Z" fill="#F59E0B" opacity="0.35"/>
  <!-- Paruh oranye -->
  <ellipse cx="136" cy="212" rx="44" ry="22" fill="#F97316"/>
  <path d="M102 214 Q136 226 168 214" fill="none" stroke="#C2410C" stroke-width="5" stroke-linecap="round"/>
  <!-- Mata -->
  <circle cx="192" cy="182" r="15" fill="#1F2933"/>
  <circle cx="196" cy="177" r="5" fill="#FFFFFF"/>
  <!-- Pipi -->
  <circle cx="222" cy="212" r="15" fill="#FB923C" opacity="0.45"/>
SVG,
            'sapi' => <<<SVG
  <!-- Kaki sapi -->
  <rect x="192" y="346" width="38" height="72" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="4"/>
  <rect x="282" y="346" width="38" height="72" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="4"/>
  <rect x="192" y="396" width="38" height="22" rx="10" fill="{$color}"/>
  <rect x="282" y="396" width="38" height="22" rx="10" fill="{$color}"/>
  <!-- Badan -->
  <ellipse cx="256" cy="336" rx="94" ry="74" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="5"/>
  <circle cx="204" cy="326" r="28" fill="{$color}"/>
  <circle cx="308" cy="350" r="24" fill="{$color}"/>
  <!-- Telinga -->
  <ellipse cx="144" cy="174" rx="34" ry="18" transform="rotate(-18 144 174)" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="4"/>
  <ellipse cx="368" cy="174" rx="34" ry="18" transform="rotate(18 368 174)" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="4"/>
  <ellipse cx="146" cy="176" rx="20" ry="10" transform="rotate(-18 146 176)" fill="#F9A8B8"/>
  <ellipse cx="366" cy="176" rx="20" ry="10" transform="rotate(18 366 176)" fill="#F9A8B8"/>
  <!-- Tanduk pendek -->
  <path d="M188 142 C168 108 186 90 206 116" fill="#E2C799"/>
  <path d="M324 142 C344 108 326 90 306 116" fill="#E2C799"/>
  <!-- Kepala -->
  <ellipse cx="256" cy="202" rx="92" ry="84" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="5"/>
  <!-- Bercak hitam di kepala -->
  <path d="M276 122 C318 134 342 164 340 198 C312 204 282 186 276 154 Z" fill="{$color}"/>
  <!-- Mata -->
  <circle cx="212" cy="186" r="15" fill="#1F2933"/>
  <circle cx="300" cy="186" r="15" fill="#1F2933"/>
  <circle cx="217" cy="180" r="5" fill="#FFFFFF"/>
  <circle cx="305" cy="180" r="5" fill="#FFFFFF"/>
  <!-- Moncong pink lebar -->
  <ellipse cx="256" cy="248" rx="68" ry="42" fill="#FBCFE8"/>
  <ellipse cx="230" cy="238" rx="10" ry="13" fill="#9D174D"/>
  <ellipse cx="282" cy="238" rx="10" ry="13" fill="#9D174D"/>
  <path d="M234 268 Q256 282 278 268" fill="none" stroke="#9D174D" stroke-width="6" stroke-linecap="round"/>
SVG,
            'kambing' => <<<SVG
  <!-- Badan -->
  <ellipse cx="266" cy="340" rx="88" ry="68" fill="#EFE4D8" stroke="#D2BFAF" stroke-width="4"/>
  <rect x="210" y="350" width="34" height="68" rx="15" fill="#D2BFAF"/>
  <rect x="274" y="350" width="34" height="68" rx="15" fill="#D2BFAF"/>
  <!-- Telinga panjang -->
  <ellipse cx="144" cy="206" rx="44" ry="18" transform="rotate(-24 144 206)" fill="{$color}"/>
  <ellipse cx="368" cy="206" rx="44" ry="18" transform="rotate(24 368 206)" fill="{$color}"/>
  <!-- Tanduk melengkung -->
  <path d="M214 134 C194 84 162 86 156 118" fill="none" stroke="#9A7B65" stroke-width="18" stroke-linecap="round"/>
  <path d="M298 134 C318 84 350 86 356 118" fill="none" stroke="#9A7B65" stroke-width="18" stroke-linecap="round"/>
  <!-- Kepala -->
  <ellipse cx="256" cy="208" rx="86" ry="82" fill="#F8EFE6" stroke="#D2BFAF" stroke-width="4"/>
  <path d="M222 140 Q256 122 290 140 L276 172 L236 172 Z" fill="{$color}"/>
  <!-- Janggut lucu -->
  <path d="M236 282 L256 322 L276 282 Z" fill="#C7B29E"/>
  <!-- Mata ramah -->
  <circle cx="216" cy="200" r="14" fill="#1F2933"/>
  <circle cx="296" cy="200" r="14" fill="#1F2933"/>
  <circle cx="220" cy="195" r="4" fill="#FFFFFF"/>
  <circle cx="300" cy="195" r="4" fill="#FFFFFF"/>
  <!-- Hidung & senyum -->
  <polygon points="244,236 268,236 256,250" fill="#D9777F"/>
  <path d="M240 258 Q256 270 272 258" fill="none" stroke="#5D4037" stroke-width="6" stroke-linecap="round"/>
  <!-- Lonceng -->
  <circle cx="256" cy="336" r="14" fill="#FBBF24" stroke="#B45309" stroke-width="4"/>
SVG,
            'ikan' => <<<SVG
  <!-- Gelembung air -->
  <circle cx="116" cy="176" r="14" fill="#BAE6FD" stroke="#7DD3FC" stroke-width="4"/>
  <circle cx="96" cy="136" r="9" fill="#BAE6FD" stroke="#7DD3FC" stroke-width="4"/>
  <circle cx="128" cy="112" r="18" fill="#E0F2FE" stroke="#7DD3FC" stroke-width="4"/>
  <!-- Sirip atas & bawah -->
  <path d="M214 166 C244 102 314 114 316 176 Z" fill="#F59E0B"/>
  <path d="M236 334 C252 384 304 380 302 332 Z" fill="#F59E0B"/>
  <!-- Sirip ekor -->
  <path d="M350 252 L428 182 C442 222 442 282 428 322 Z" fill="#F59E0B"/>
  <!-- Badan ikan oval ramah -->
  <ellipse cx="246" cy="252" rx="134" ry="96" fill="{$color}"/>
  <!-- Garis lengkung ceria -->
  <path d="M232 162 C256 206 256 298 232 342" fill="none" stroke="#FFFFFF" stroke-width="20" stroke-linecap="round" opacity="0.9"/>
  <path d="M294 170 C314 212 314 292 294 334" fill="none" stroke="#FBBF24" stroke-width="18" stroke-linecap="round"/>
  <!-- Sirip samping -->
  <path d="M212 272 C246 256 276 286 244 316 C224 322 204 296 212 272 Z" fill="#FBBF24"/>
  <!-- Mata & senyum -->
  <circle cx="174" cy="232" r="24" fill="#FFFFFF"/>
  <circle cx="170" cy="232" r="13" fill="#1F2933"/>
  <circle cx="175" cy="226" r="5" fill="#FFFFFF"/>
  <circle cx="192" cy="264" r="14" fill="#FCA5A5" opacity="0.6"/>
  <path d="M120 264 Q146 284 170 268" fill="none" stroke="#0C4A6E" stroke-width="7" stroke-linecap="round"/>
SVG,
            'kuda' => <<<SVG
  <!-- Surai gelap rapi -->
  <path d="M254 110 C316 112 362 166 364 252 L318 262 L276 150 Z" fill="#3E2723"/>
  <!-- Badan & kaki -->
  <ellipse cx="282" cy="342" rx="92" ry="68" fill="{$color}"/>
  <rect x="218" y="352" width="34" height="68" rx="14" fill="{$color}"/>
  <rect x="306" y="352" width="34" height="68" rx="14" fill="{$color}"/>
  <rect x="218" y="400" width="34" height="20" rx="8" fill="#2E1C16"/>
  <rect x="306" y="400" width="34" height="20" rx="8" fill="#2E1C16"/>
  <!-- Leher -->
  <path d="M204 202 L204 342 L316 342 L308 202 Z" fill="{$color}"/>
  <!-- Telinga -->
  <polygon points="184,158 176,102 222,138" fill="{$color}"/>
  <polygon points="274,148 286,98 310,148" fill="{$color}"/>
  <!-- Kepala -->
  <ellipse cx="234" cy="198" rx="74" ry="76" fill="{$color}"/>
  <!-- Moncong cream -->
  <ellipse cx="210" cy="238" rx="64" ry="44" fill="#EAD4C0"/>
  <!-- Poni surai lucu -->
  <path d="M214 134 C236 118 268 124 274 156 C250 158 228 152 214 134 Z" fill="#3E2723"/>
  <!-- Mata -->
  <circle cx="204" cy="186" r="14" fill="#1F2933"/>
  <circle cx="268" cy="186" r="14" fill="#1F2933"/>
  <circle cx="208" cy="181" r="4" fill="#FFFFFF"/>
  <circle cx="272" cy="181" r="4" fill="#FFFFFF"/>
  <!-- Lubang hidung & senyum -->
  <circle cx="186" cy="232" r="8" fill="#5D4037"/>
  <circle cx="232" cy="232" r="8" fill="#5D4037"/>
  <path d="M188 258 Q210 272 232 258" fill="none" stroke="#5D4037" stroke-width="6" stroke-linecap="round"/>
SVG,
            'gajah' => <<<SVG
  <!-- Telinga lebar ramah -->
  <ellipse cx="144" cy="216" rx="78" ry="88" fill="{$color}"/>
  <ellipse cx="368" cy="216" rx="78" ry="88" fill="{$color}"/>
  <ellipse cx="148" cy="220" rx="52" ry="62" fill="#F7C5D0"/>
  <ellipse cx="364" cy="220" rx="52" ry="62" fill="#F7C5D0"/>
  <!-- Badan & kaki kokoh -->
  <ellipse cx="256" cy="338" rx="94" ry="76" fill="{$color}"/>
  <rect x="190" y="348" width="44" height="72" rx="18" fill="{$color}"/>
  <rect x="278" y="348" width="44" height="72" rx="18" fill="{$color}"/>
  <ellipse cx="212" cy="414" rx="22" ry="9" fill="#CBD5E1"/>
  <ellipse cx="300" cy="414" rx="22" ry="9" fill="#CBD5E1"/>
  <!-- Kepala -->
  <circle cx="256" cy="206" r="90" fill="#8FA6B2"/>
  <!-- Gading kecil -->
  <path d="M214 252 Q194 274 204 286 Q220 278 228 260 Z" fill="#FFFDF5"/>
  <path d="M298 252 Q318 274 308 286 Q292 278 284 260 Z" fill="#FFFDF5"/>
  <!-- Mata -->
  <circle cx="212" cy="196" r="15" fill="#1F2933"/>
  <circle cx="300" cy="196" r="15" fill="#1F2933"/>
  <circle cx="217" cy="190" r="5" fill="#FFFFFF"/>
  <circle cx="305" cy="190" r="5" fill="#FFFFFF"/>
  <!-- Pipi -->
  <circle cx="192" cy="224" r="15" fill="#F9A8B8" opacity="0.6"/>
  <circle cx="320" cy="224" r="15" fill="#F9A8B8" opacity="0.6"/>
  <!-- Belalai melengkung ceria -->
  <path d="M256 226 C256 286 302 310 326 282 C340 264 328 242 308 250" fill="none" stroke="#8FA6B2" stroke-width="34" stroke-linecap="round"/>
SVG,
            'singa' => <<<'SVG'
  <!-- Ekor berujung rumbai -->
  <path d="M332 362 C392 360 408 310 386 284" fill="none" stroke="#FBBF24" stroke-width="20" stroke-linecap="round"/>
  <ellipse cx="386" cy="276" rx="22" ry="16" fill="#C2410C"/>
  <!-- Badan -->
  <ellipse cx="256" cy="346" rx="84" ry="68" fill="#FBBF24"/>
  <rect x="206" y="346" width="38" height="72" rx="19" fill="#FBBF24"/>
  <rect x="268" y="346" width="38" height="72" rx="19" fill="#FBBF24"/>
  <!-- Surai singa scalloped ceria -->
  <g fill="#D97706">
    <circle cx="256" cy="108" r="42"/>
    <circle cx="316" cy="128" r="42"/>
    <circle cx="348" cy="184" r="42"/>
    <circle cx="338" cy="244" r="42"/>
    <circle cx="290" cy="284" r="42"/>
    <circle cx="222" cy="284" r="42"/>
    <circle cx="174" cy="244" r="42"/>
    <circle cx="164" cy="184" r="42"/>
    <circle cx="196" cy="128" r="42"/>
  </g>
  <!-- Telinga bulat -->
  <circle cx="182" cy="142" r="24" fill="#FBBF24"/>
  <circle cx="330" cy="142" r="24" fill="#FBBF24"/>
  <circle cx="182" cy="142" r="12" fill="#FDE68A"/>
  <circle cx="330" cy="142" r="12" fill="#FDE68A"/>
  <!-- Wajah -->
  <circle cx="256" cy="202" r="82" fill="#FCD34D"/>
  <!-- Moncong cream -->
  <ellipse cx="256" cy="236" rx="46" ry="32" fill="#FFFBEB"/>
  <!-- Mata -->
  <circle cx="218" cy="192" r="15" fill="#1F2933"/>
  <circle cx="294" cy="192" r="15" fill="#1F2933"/>
  <circle cx="223" cy="186" r="5" fill="#FFFFFF"/>
  <circle cx="299" cy="186" r="5" fill="#FFFFFF"/>
  <!-- Pipi -->
  <circle cx="198" cy="218" r="14" fill="#FB923C" opacity="0.45"/>
  <circle cx="314" cy="218" r="14" fill="#FB923C" opacity="0.45"/>
  <!-- Hidung segitiga & kumis -->
  <polygon points="242,220 270,220 256,236" fill="#9A3412"/>
  <path d="M256 236 L256 248 M236 250 Q246 260 256 248 Q266 260 276 250" fill="none" stroke="#9A3412" stroke-width="6" stroke-linecap="round"/>
SVG,
            'bola' => <<<SVG
  <circle cx="256" cy="252" r="148" fill="#FFFDF9" stroke="#CBD5E1" stroke-width="6"/>
  <!-- Panel warna bola ceria -->
  <path d="M256 104 A148 148 0 0 1 398 208 Q326 240 256 226 Q186 240 114 208 A148 148 0 0 1 256 104 Z" fill="{$color}"/>
  <path d="M148 352 Q216 300 256 320 Q296 300 364 352 A148 148 0 0 1 148 352 Z" fill="#2F6F9F"/>
  <circle cx="256" cy="252" r="44" fill="#FBBF24"/>
  <path d="M256 104 Q236 164 256 208 M256 296 Q272 346 256 400 M108 252 Q168 232 212 252 M300 252 Q344 232 404 252" fill="none" stroke="#1F2933" stroke-width="6" stroke-linecap="round" opacity="0.22"/>
  <ellipse cx="198" cy="166" rx="34" ry="14" transform="rotate(-32 198 166)" fill="#FFFFFF" opacity="0.35"/>
SVG,
            'buku' => <<<SVG
  <!-- Sampul keras buku terbuka -->
  <rect x="88" y="148" width="336" height="232" rx="22" fill="{$color}"/>
  <!-- Halaman kiri & kanan -->
  <path d="M108 158 C164 146 218 154 256 178 L256 364 C218 344 164 342 108 354 Z" fill="#FFFDF9" stroke="#D1DAE4" stroke-width="4"/>
  <path d="M404 158 C348 146 294 154 256 178 L256 364 C294 344 348 342 404 354 Z" fill="#F4F8FB" stroke="#D1DAE4" stroke-width="4"/>
  <!-- Garis teks rapi -->
  <line x1="134" y1="202" x2="226" y2="208" stroke="#94A3B8" stroke-width="8" stroke-linecap="round"/>
  <line x1="134" y1="234" x2="226" y2="240" stroke="#94A3B8" stroke-width="8" stroke-linecap="round"/>
  <line x1="134" y1="266" x2="206" y2="270" stroke="#94A3B8" stroke-width="8" stroke-linecap="round"/>
  <!-- Ilustrasi kecil di halaman kanan -->
  <rect x="284" y="196" width="94" height="68" rx="12" fill="#DCEBF7"/>
  <circle cx="331" cy="230" r="18" fill="#F59E0B"/>
  <line x1="284" y1="292" x2="374" y2="288" stroke="#94A3B8" stroke-width="8" stroke-linecap="round"/>
  <!-- Pita pembatas buku -->
  <path d="M244 176 L268 176 L268 398 L256 384 L244 398 Z" fill="#E53935"/>
SVG,
            'kursi' => <<<SVG
  <!-- Kaki belakang -->
  <rect x="164" y="128" width="24" height="278" rx="12" fill="#6D4C41"/>
  <rect x="324" y="128" width="24" height="278" rx="12" fill="#6D4C41"/>
  <!-- Sandaran kayu hangat -->
  <rect x="158" y="140" width="196" height="58" rx="18" fill="{$color}"/>
  <rect x="158" y="210" width="196" height="28" rx="12" fill="{$color}"/>
  <!-- Kaki depan -->
  <rect x="152" y="290" width="24" height="126" rx="12" fill="#795548"/>
  <rect x="336" y="290" width="24" height="126" rx="12" fill="#795548"/>
  <!-- Dudukan -->
  <rect x="136" y="272" width="240" height="36" rx="16" fill="#A17968"/>
  <rect x="150" y="264" width="212" height="14" rx="7" fill="#C8A99A" opacity="0.5"/>
SVG,
            'meja' => <<<SVG
  <!-- Kaki meja kokoh -->
  <rect x="128" y="232" width="26" height="180" rx="12" fill="#4E342E"/>
  <rect x="358" y="232" width="26" height="180" rx="12" fill="#4E342E"/>
  <rect x="174" y="238" width="22" height="144" rx="10" fill="#3E2723"/>
  <rect x="316" y="238" width="22" height="144" rx="10" fill="#3E2723"/>
  <!-- Laci bawah -->
  <rect x="116" y="218" width="280" height="44" rx="10" fill="{$color}"/>
  <rect x="226" y="234" width="60" height="10" rx="5" fill="#D7CCC8"/>
  <!-- Daun meja -->
  <rect x="90" y="186" width="332" height="38" rx="16" fill="#8D6E63"/>
  <!-- Tempat pensil di atas meja -->
  <rect x="320" y="138" width="36" height="48" rx="8" fill="#2F6F9F"/>
  <rect x="328" y="108" width="9" height="34" rx="4" fill="#F59E0B" transform="rotate(-10 332 124)"/>
  <rect x="342" y="112" width="9" height="30" rx="4" fill="#E53935" transform="rotate(8 346 126)"/>
SVG,
            'gelas' => <<<SVG
  <!-- Badan gelas bening -->
  <polygon points="154,132 358,132 326,396 186,396" fill="#E0F7FA" stroke="#0097A7" stroke-width="10" stroke-linejoin="round"/>
  <!-- Air segar di dalam gelas -->
  <path d="M168 232 Q216 218 256 232 T344 232 L320 388 L192 388 Z" fill="{$color}" opacity="0.72"/>
  <ellipse cx="256" cy="232" rx="88" ry="14" fill="#80DEEA"/>
  <!-- Garis bibir gelas & kilau -->
  <ellipse cx="256" cy="132" rx="102" ry="18" fill="#F4FCFD" stroke="#0097A7" stroke-width="8"/>
  <path d="M192 174 L208 352" stroke="#FFFFFF" stroke-width="14" stroke-linecap="round" opacity="0.65"/>
SVG,
            'piring' => <<<SVG
  <!-- Piring makan keramik bersih -->
  <circle cx="256" cy="252" r="168" fill="#F8FAFC" stroke="#CBD5E1" stroke-width="10"/>
  <circle cx="256" cy="252" r="142" fill="#FFFFFF" stroke="{$color}" stroke-width="12" stroke-dasharray="18 14"/>
  <circle cx="256" cy="252" r="108" fill="#F1F5F9" stroke="#CBD5E1" stroke-width="4"/>
  <ellipse cx="216" cy="206" rx="42" ry="18" transform="rotate(-35 216 206)" fill="#FFFFFF" opacity="0.9"/>
SVG,
            'sendok' => <<<'SVG'
  <!-- Sendok makan mengilap miring -->
  <g transform="rotate(35 256 256)">
    <!-- Gagang sendok -->
    <path d="M238 228 L274 228 L280 416 C280 434 232 434 232 416 Z" fill="#94A3B8" stroke="#64748B" stroke-width="6"/>
    <path d="M248 252 L248 394" stroke="#E2E8F0" stroke-width="8" stroke-linecap="round"/>
    <!-- Kepala sendok oval -->
    <ellipse cx="256" cy="164" rx="68" ry="90" fill="#CBD5E1" stroke="#64748B" stroke-width="6"/>
    <ellipse cx="256" cy="166" rx="48" ry="66" fill="#E2E8F0"/>
    <ellipse cx="232" cy="132" rx="16" ry="30" transform="rotate(-14 232 132)" fill="#FFFFFF" opacity="0.8"/>
  </g>
SVG,
            'tas' => <<<SVG
  <!-- Pegangan atas ransel -->
  <path d="M214 128 C214 90 298 90 298 128" fill="none" stroke="#4A148C" stroke-width="20" stroke-linecap="round"/>
  <!-- Saku samping -->
  <rect x="118" y="266" width="38" height="104" rx="16" fill="#6A1B9A"/>
  <rect x="356" y="266" width="38" height="104" rx="16" fill="#6A1B9A"/>
  <!-- Badan ransel ungu -->
  <rect x="142" y="130" width="228" height="270" rx="64" fill="{$color}"/>
  <!-- Tutup melengkung -->
  <path d="M142 204 C142 152 184 130 256 130 C328 130 370 152 370 204 L370 232 C370 250 356 264 338 264 L174 264 C156 264 142 250 142 232 Z" fill="#9C27B0"/>
  <!-- Saku depan -->
  <rect x="176" y="284" width="160" height="92" rx="24" fill="#6A1B9A"/>
  <line x1="196" y1="306" x2="316" y2="306" stroke="#FCD34D" stroke-width="8" stroke-linecap="round"/>
  <!-- Gesper tag lucu -->
  <circle cx="256" cy="244" r="18" fill="#FBBF24" stroke="#B45309" stroke-width="4"/>
SVG,
            'sepatu' => <<<SVG
  <!-- Sol karet putih tebal -->
  <path d="M96 340 L416 340 C424 340 426 368 412 374 L102 374 C90 374 88 340 96 340 Z" fill="#FFFFFF" stroke="#94A3B8" stroke-width="6"/>
  <!-- Badan sepatu olahraga -->
  <path d="M114 200 L184 186 L246 244 L366 258 C402 264 420 296 416 340 L102 340 L104 220 C104 208 108 202 114 200 Z" fill="{$color}"/>
  <!-- Ujung kaki putih -->
  <path d="M354 256 C394 262 418 294 416 340 L338 340 C338 298 342 268 354 256 Z" fill="#F1F5F9"/>
  <!-- Garis desain & tali putih -->
  <rect x="122" y="196" width="54" height="22" rx="10" fill="#607D8B"/>
  <line x1="234" y1="242" x2="262" y2="226" stroke="#FFFFFF" stroke-width="10" stroke-linecap="round"/>
  <line x1="258" y1="256" x2="286" y2="240" stroke="#FFFFFF" stroke-width="10" stroke-linecap="round"/>
  <line x1="282" y1="270" x2="310" y2="254" stroke="#FFFFFF" stroke-width="10" stroke-linecap="round"/>
SVG,
            default => <<<SVG
  <!-- Kancing atas topi -->
  <circle cx="256" cy="148" r="16" fill="#E65100"/>
  <!-- Kubah topi -->
  <path d="M116 292 C116 198 182 152 256 152 C330 152 396 198 396 292 Z" fill="{$color}"/>
  <path d="M198 292 C198 202 224 152 256 152 C288 152 314 202 314 292 Z" fill="#FFB74D" opacity="0.45"/>
  <!-- Garis jahitan panel -->
  <path d="M200 288 C202 210 226 158 256 152 M312 288 C310 210 286 158 256 152" fill="none" stroke="#E65100" stroke-width="5" stroke-dasharray="10 8"/>
  <!-- Lidah/pelindung topi -->
  <path d="M108 290 C168 272 288 272 414 316 C432 324 424 350 388 352 L114 328 C98 326 94 296 108 290 Z" fill="#D84315"/>
SVG,
        };
    }
}

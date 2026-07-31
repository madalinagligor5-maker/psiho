<?php /** Infografic: bucla sistemica gânduri -> emoții -> comportamente. */ ?>
<div class="infografic reveal">
  <p class="infografic__titlu">Ce simți, ce gândești și ce faci se hrănesc unele pe altele — o buclă.</p>
  <svg viewBox="0 0 300 280" fill="none" xmlns="http://www.w3.org/2000/svg" role="img"
       aria-label="Bucla sistemică: gândurile hrănesc emoțiile, emoțiile hrănesc comportamentele, comportamentele hrănesc gândurile.">
    <!-- Sageti curbe intre noduri (in sensul acelor de ceas) -->
    <g stroke="var(--piatra)" stroke-width="1.6" fill="none" stroke-linecap="round">
      <path d="M188 62 C 224 84 240 120 236 150"/>
      <path d="M198 214 C 170 230 130 230 104 216"/>
      <path d="M64 150 C 60 118 78 82 112 62"/>
    </g>
    <g fill="var(--piatra)">
      <path d="M236 150 l -7 -6 8 -1 3 -7 z"/>
      <path d="M104 216 l 8 -3 -3 8 4 6 z"/>
      <path d="M112 62 l -1 8 -7 -4 -7 2 z"/>
    </g>
    <!-- Noduri -->
    <circle cx="150" cy="48" r="38" fill="var(--verde-pal)"/>
    <circle cx="238" cy="196" r="38" fill="var(--lavanda-pal)"/>
    <circle cx="62"  cy="196" r="38" fill="var(--azur)"/>
    <!-- Etichete -->
    <text x="150" y="52" text-anchor="middle" font-weight="700" font-size="14">gânduri</text>
    <text x="238" y="200" text-anchor="middle" font-weight="700" font-size="14">emoții</text>
    <text x="62"  y="192" text-anchor="middle" font-weight="700" font-size="13">compor-</text>
    <text x="62"  y="206" text-anchor="middle" font-weight="700" font-size="13">tamente</text>
  </svg>
</div>

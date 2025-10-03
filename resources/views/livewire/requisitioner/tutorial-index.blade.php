<div class="w-full min-h-screen bg-white">
  <div class="relative w-full">
    <!-- Top band: Administrative Operations & Support -->
    <section class="relative h-[40vh] bg-[#f6dfd4] border-b-[2px] border-[#e6cfc6]">
      <div class="absolute left-4 top-1/2 mt-28 -translate-y-1/2 -rotate-90 origin-left text-sm font-semibold tracking-tight text-black">
        Administrative Operations &amp; Support
      </div>

      <!-- First Box: Navigate the SEARCH Dashboard -->
<div x-data="{ open: false, videoSrc: '' }" class="absolute top-8 left-20 border border-black bg-white shadow px-1 py-1 max-w-28">
    <!-- Image with hover tooltip -->
    <div class="relative group">
        <img
            src="{{ asset('images/tutorial_images/navigate_search.jpg') }}"
            alt="Navigate the SEARCH Dashboard"
            class="w-28 h-28 object-contain mx-auto transition-transform duration-200 group-hover:scale-105 cursor-pointer"
            @click="
                videoSrc = 'https://drive.google.com/file/d/12J5IwRWEsXAxg1DjnDSppDaypeMVtKCD/preview';
                open = true
            "
        />
        <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-primary-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
            Navigate the SEARCH Dashboard
        </div>
    </div>

    <!-- Modal -->
    <div
        x-show="open"
        x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50"
    >
        <div class="bg-black w-full h-full relative flex items-center justify-center">
            <!-- Close Button -->
            <button
                @click="open = false; videoSrc = ''"
                class="absolute top-4 right-6 text-white text-3xl z-50"
            >
                ✕
            </button>

            <!-- Google Drive Video (fullscreen) -->
            <iframe
                x-show="videoSrc"
                :src="videoSrc"
                class="w-full h-full"
                allow="autoplay"
                allowfullscreen
            ></iframe>
        </div>
    </div>
</div>







      <!-- Second Box: Create Travel Order -->
    <div
        x-data="{ open: false, videoSrc: '' }"
        class="absolute top-8 left-[500px] border border-black bg-white shadow px-1 py-1 max-w-28"
    >
        <!-- Image with hover tooltip -->
        <div class="relative group">
            <img
                src="{{ asset('images/tutorial_images/travel_order.jpg') }}"
                alt="Create Travel Order"
                class="w-28 h-28 object-contain mx-auto transition-transform duration-200 group-hover:scale-105 cursor-pointer"
                @click="
                    videoSrc = 'https://drive.google.com/file/d/10CqYH-ybZG5Cka4jLU_1MffJe-6lk1bU/preview';
                    open = true
                "
            />
            <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-primary-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                Create Travel Order
            </div>
        </div>

        <!-- Modal -->
        <div
            x-show="open"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50"
        >
            <div class="bg-black w-full h-full relative flex items-center justify-center">
                <!-- Close Button -->
                <button
                    @click="open = false; videoSrc = ''"
                    class="absolute top-4 right-6 text-white text-3xl z-50"
                >
                    ✕
                </button>

                <!-- Google Drive Video (fullscreen) -->
                <iframe
                    x-show="videoSrc"
                    :src="videoSrc"
                    class="w-full h-full"
                    allow="autoplay"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>


      <!-- Third Box: Request Vehicle -->
     <div
    x-data="{ open: false, videoSrc: '' }"
    class="absolute top-8 left-[850px] border border-black bg-white shadow px-1 py-1 max-w-28"
>
    <!-- Image with hover tooltip -->
    <div class="relative group">
        <img
            src="{{ asset('images/tutorial_images/request_vehicle.jpg') }}"
            alt="Request Vehicle"
            class="w-28 h-28 object-contain mx-auto transition-transform duration-200 group-hover:scale-105 cursor-pointer"
            @click="
                videoSrc = 'https://drive.google.com/file/d/1wWwEDgRLrVtdxdVePMGnDBfSIGT6pv3R/preview';
                open = true
            "
        />
        <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-primary-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
            Request Vehicle
        </div>
    </div>

    <!-- Modal -->
    <div
        x-show="open"
        x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50"
    >
        <div class="bg-black w-full h-full relative flex items-center justify-center">
            <!-- Close Button -->
            <button
                @click="open = false; videoSrc = ''"
                class="absolute top-4 right-6 text-white text-3xl z-50"
            >
                ✕
            </button>

            <!-- Google Drive Video (fullscreen) -->
            <iframe
                x-show="videoSrc"
                :src="videoSrc"
                class="w-full h-full"
                allow="autoplay"
                allowfullscreen
            ></iframe>
        </div>
    </div>
</div>


      <!-- Fourth Box: Create Activity Design -->
<div
    x-data="{ open: false, videoSrc: '' }"
    class="absolute top-60 left-20 border border-black bg-white shadow px-1 py-1 max-w-28"
>
    <!-- Image with hover tooltip -->
    <div class="relative group">
        <img
            src="{{ asset('images/tutorial_images/activity_design.jpg') }}"
            alt="Create Activity Design"
            class="w-28 h-28 object-contain mx-auto transition-transform duration-200 group-hover:scale-105 cursor-pointer"
            @click="
                videoSrc = 'https://drive.google.com/file/d/1G5IKYvFTgnf5_fS-gc52b_vIus9u5rDG/preview';
                open = true
            "
        />
        <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-primary-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
            Create Activity Design
        </div>
    </div>

    <!-- Modal -->
    <div
        x-show="open"
        x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50"
    >
        <div class="bg-black w-full h-full relative flex items-center justify-center">
            <!-- Close Button -->
            <button
                @click="open = false; videoSrc = ''"
                class="absolute top-4 right-6 text-white text-3xl z-50"
            >
                ✕
            </button>

            <!-- Google Drive Video (fullscreen) -->
            <iframe
                x-show="videoSrc"
                :src="videoSrc"
                class="w-full h-full"
                allow="autoplay"
                allowfullscreen
            ></iframe>
        </div>
    </div>
</div>

    </section>

    <!-- Middle band: Financials -->
    <section class="relative h-[40vh] bg-[#cce0c1] border-b-[2px] border-[#a2c69b]">
      <div class="absolute left-4 top-1/2 -translate-y-1/2 -rotate-90 origin-left text-sm font-semibold tracking-tight text-black">
        Financials
      </div>

      <!-- First Box: Disbursement Voucher -->
     <div
        x-data="{ open: false, videoSrc: '' }"
        class="absolute top-8 left-[500px] border border-black bg-white shadow px-1 py-1 max-w-28"
    >
        <!-- Image with hover tooltip -->
        <div class="relative group">
            <img
                src="{{ asset('images/tutorial_images/create_voucher.jpg') }}"
                alt="Disbursement Voucher"
                class="w-28 h-28 object-contain mx-auto transition-transform duration-200 group-hover:scale-105 cursor-pointer"
                @click="
                    videoSrc = 'https://drive.google.com/file/d/1VKMdSCJO25rJtRZOYS2XDoSJ5IXGGx5w/preview';
                    open = true
                "
            />
            <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-primary-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                Disbursement Voucher
            </div>
        </div>

        <!-- Modal -->
        <div
            x-show="open"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50"
        >
            <div class="bg-black w-full h-full relative flex items-center justify-center">
                <!-- Close Button -->
                <button
                    @click="open = false; videoSrc = ''"
                    class="absolute top-4 right-6 text-white text-3xl z-50"
                >
                    ✕
                </button>

                <!-- Google Drive Video (fullscreen) -->
                <iframe
                    x-show="videoSrc"
                    :src="videoSrc"
                    class="w-full h-full"
                    allow="autoplay"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>


      <!-- Second Box: Cash Advance -->
      <div class="absolute top-[175px] left-[505px] border border-black bg-gray-600 shadow px-1 py-1 text-white max-w-28 text-sm">
        Cash Advance
      </div>

      <!-- Third Box: Liquidate Cash Advance -->
     <div
            x-data="{ open: false, videoSrc: '' }"
            class="absolute top-60 left-[500px] border border-black bg-white shadow px-1 py-1 max-w-28"
        >
            <!-- Image with hover tooltip -->
            <div class="relative group">
                <img
                    src="{{ asset('images/tutorial_images/liquidation_report.jpg') }}"
                    alt="Liquidate Cash Advance (Liquidation Report)"
                    class="w-28 h-28 object-contain mx-auto transition-transform duration-200 group-hover:scale-105 cursor-pointer"
                    @click="
                        videoSrc = 'https://drive.google.com/file/d/1L0B47YrHoLfBbEZDQEGwZyiOlQImJTBc/preview';
                        open = true
                    "
                />
                <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-primary-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                    Liquidate Cash Advance (Liquidation Report)
                </div>
            </div>

            <!-- Modal -->
            <div
                x-show="open"
                x-transition
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50"
            >
                <div class="bg-black w-full h-full relative flex items-center justify-center">
                    <!-- Close Button -->
                    <button
                        @click="open = false; videoSrc = ''"
                        class="absolute top-4 right-6 text-white text-3xl z-50"
                    >
                        ✕
                    </button>

                    <!-- Google Drive Video (fullscreen) -->
                    <iframe
                        x-show="videoSrc"
                        :src="videoSrc"
                        class="w-full h-full"
                        allow="autoplay"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </div>


      <!-- Fourth Box: Track Disbursement Voucher -->
     <div
            x-data="{ open: false, videoSrc: '' }"
            class="absolute top-8 left-[850px] border border-black bg-white shadow px-1 py-1 max-w-28"
        >
            <!-- Image with hover tooltip -->
            <div class="relative group">
                <img
                    src="{{ asset('images/tutorial_images/monitor_voucher.jpg') }}"
                    alt="Track Disbursement Voucher"
                    class="w-28 h-28 object-contain mx-auto transition-transform duration-200 group-hover:scale-105 cursor-pointer"
                    @click="
                        videoSrc = 'https://drive.google.com/file/d/1K0TBdBylOZVpj9EtZMUGO-H36lq1h-7J/preview';
                        open = true
                    "
                />
                <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-primary-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                    Track Disbursement Voucher
                </div>
            </div>

            <!-- Modal -->
            <div
                x-show="open"
                x-transition
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50"
            >
                <div class="bg-black w-full h-full relative flex items-center justify-center">
                    <!-- Close Button -->
                    <button
                        @click="open = false; videoSrc = ''"
                        class="absolute top-4 right-6 text-white text-3xl z-50"
                    >
                        ✕
                    </button>

                    <!-- Google Drive Video (fullscreen) -->
                    <iframe
                        x-show="videoSrc"
                        :src="videoSrc"
                        class="w-full h-full"
                        allow="autoplay"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </div>


      <!-- Fifth Box: Track Liquidation Report -->
     <div
    x-data="{ open: false, videoSrc: '' }"
    class="absolute top-60 left-[850px] border border-black bg-white shadow px-1 py-1 max-w-28"
>
    <!-- Image with hover tooltip -->
    <div class="relative group">
        <img
            src="{{ asset('images/tutorial_images/monitor_liquidation.jpg') }}"
            alt="Track Liquidation Report"
            class="w-28 h-28 object-contain mx-auto transition-transform duration-200 group-hover:scale-105 cursor-pointer"
            @click="
                videoSrc = 'https://drive.google.com/file/d/1K0TBdBylOZVpj9EtZMUGO-H36lq1h-7J/preview';
                open = true
            "
        />
        <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-primary-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
            Track Liquidation Report
        </div>
    </div>

    <!-- Modal -->
    <div
        x-show="open"
        x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50"
    >
        <div class="bg-black w-full h-full relative flex items-center justify-center">
            <!-- Close Button -->
            <button
                @click="open = false; videoSrc = ''"
                class="absolute top-4 right-6 text-white text-3xl z-50"
            >
                ✕
            </button>

            <!-- Google Drive Video (fullscreen) -->
            <iframe
                x-show="videoSrc"
                :src="videoSrc"
                class="w-full h-full"
                allow="autoplay"
                allowfullscreen
            ></iframe>
        </div>
    </div>
</div>

    </section>

    <!-- Bottom band: Others -->
    <section class="relative h-[40vh] bg-[#2f5b2f]">
      <div class="absolute left-4 top-1/2 -translate-y-1/2 -rotate-90 origin-left text-sm font-semibold tracking-tight text-white">
        Others
      </div>
    </section>

    <!-- SVG Arrows Layer -->
    <svg class="absolute inset-0 w-full h-full pointer-events-none">
      <!-- Arrow: Create Travel Order -> Request Vehicle -->
      <line x1="612" y1="100" x2="843" y2="100" stroke="black" stroke-width="1.5" marker-end="url(#arrowhead)" />

      <!-- Arrow: Create Travel Order -> Disbursement Voucher -->
      <line x1="555" y1="154" x2="555" y2="403" stroke="black" stroke-width="1.5" marker-end="url(#arrowhead)" />

      <!-- Arrow: Create Activity Design -> Disbursement Voucher -->
      <line x1="140" y1="471" x2="140" y2="362" stroke="black" stroke-width="1.5" />
      <line x1="140" y1="470" x2="493" y2="470" stroke="black" stroke-width="1.5" marker-end="url(#arrowhead)" />

      <!-- Broken line: Disbursement Voucher -> Track Disbursement Voucher -->
       <line x1="612" y1="470" x2="846" y2="470" stroke="black" stroke-width="1.5" stroke-dasharray="6 4" />
      {{-- <path d="M700 478 L850 200" stroke="black" stroke-width="2" stroke-dasharray="6 4" marker-end="url(#arrowhead)" /> --}}

      <!-- Broken line: Liquidate Cash Advance -> Track Liquidation Report -->
    <line x1="612" y1="670" x2="846" y2="670" stroke="black" stroke-width="1.5" stroke-dasharray="6 4" />
      {{-- <path d="M640 480 L850 480" stroke="black" stroke-width="2" stroke-dasharray="6 4" marker-end="url(#arrowhead)" /> --}}

      <!-- Line: Disbursement Voucher -> Cash Advance -->
      <line x1="555" y1="532" x2="555" y2="553" stroke="black" stroke-width="1.5"  />

      <!-- Arrow: Cash Advance -> Liquidate Cash Advance -->
      <line x1="555" y1="582" x2="555" y2="612" stroke="black" stroke-width="1.5" marker-end="url(#arrowhead)" />

      <!-- Define arrowhead -->
      <defs>
        <marker id="arrowhead" markerWidth="10" markerHeight="7" refX="5" refY="3.5" orient="auto">
          <polygon points="0 0, 10 3.5, 0 7" fill="black" />
        </marker>
      </defs>
    </svg>

  </div>
</div>

<div class="px-4">
<style>
    .tooltip-wrapper {
        position: relative;
        display: inline-block;
        cursor: help;
    }

    .tooltip-box {
        visibility: hidden;
        opacity: 0;
        width: max-content;
        max-width: 250px;
        background: #05691a;
        color: #fff;
        text-align: left;
        padding: 8px 10px;
        border-radius: 6px;
        font-size: 0.85rem;
        position: absolute;
        z-index: 9999;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        transition: opacity 0.2s ease-in-out;
        white-space: normal;
        z-index: 1000 !important;

    }

    .tooltip-wrapper:hover .tooltip-box {
        visibility: visible;
        opacity: 1;
    }

    .tooltip-box::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }
</style>
    @php
        $names = $getRecord()->applicants()
            ->with('employee_information')
            ->get()
            ->pluck('employee_information.full_name');

        $allNames = $names->implode(', ');
        $limitedNames = str($allNames)->limit(40);
    @endphp

    <div class="tooltip-wrapper">
        {{ $limitedNames }}

        <div class="tooltip-box">
            {{ $allNames }}
        </div>
    </div>

</div>

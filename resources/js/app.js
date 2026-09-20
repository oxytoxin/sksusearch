import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm'
import flatpickr from 'flatpickr'
import "flatpickr/dist/themes/material_green.css"
import tippy from 'tippy.js'
import 'tippy.js/dist/tippy.css';
import Tooltip from "@ryangjchandler/alpine-tooltip";

window.flatpickr = flatpickr;
Alpine.plugin(Tooltip);

window.Alpine = Alpine
window.Livewire = Livewire
window.tippy = tippy;
Livewire.start()

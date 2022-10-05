import Alpine from 'alpinejs'
import Focus from '@alpinejs/focus'
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
import NotificationsAlpinePlugin from '../../vendor/filament/notifications/dist/module.esm'
import flatpickr from 'flatpickr'
import "flatpickr/dist/themes/material_green.css"
import tippy from 'tippy.js'
import 'tippy.js/dist/tippy.css';
import Tooltip from "@ryangjchandler/alpine-tooltip";

window.flatpickr = flatpickr;
Alpine.plugin(Focus)
Alpine.plugin(FormsAlpinePlugin)
Alpine.plugin(NotificationsAlpinePlugin)
Alpine.plugin(Tooltip);

window.Alpine = Alpine
window.tippy = tippy;
Alpine.start()

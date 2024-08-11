import '../../vendor/wire-elements/pro/resources/js/overlay-component.js';
import parsePhoneNumber from 'libphonenumber-js';
import flatpickr from 'flatpickr';
import monthSelectPlugin from 'flatpickr/dist/plugins/monthSelect';
import { nl } from "flatpickr/dist/l10n/nl.js";
import moment from 'moment-timezone';
import 'moment/dist/locale/nl';

window.parsePhoneNumber = parsePhoneNumber;
window.flatpickr = flatpickr;
window.monthSelectPlugin = monthSelectPlugin;
window.nl = nl;
window.moment = moment;

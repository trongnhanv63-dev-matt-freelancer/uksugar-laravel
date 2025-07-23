import './bootstrap';

import 'tom-select/dist/css/tom-select.css';
import '../css/components/tom-select.css';

import Alpine from 'alpinejs';
import TomSelect from 'tom-select';

// Import logic cho component live-table
import liveTable from './components/admin/liveTable';

window.TomSelect = TomSelect;
window.liveTable = liveTable;

window.Alpine = Alpine;
Alpine.start();

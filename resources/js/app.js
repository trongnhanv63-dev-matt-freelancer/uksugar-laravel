import './bootstrap';

import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';
import '../css/components/tom-select.css';

import Alpine from 'alpinejs';
import liveTable from './components/admin/liveTable';
import permissionGroup from './components/admin/permissionGroups';

window.TomSelect = TomSelect;
window.liveTable = liveTable;
window.permissionGroup = permissionGroup;

window.Alpine = Alpine;

Alpine.start();

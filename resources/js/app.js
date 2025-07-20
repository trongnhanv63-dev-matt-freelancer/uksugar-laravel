import './bootstrap';

import 'tom-select/dist/css/tom-select.css';
import '../css/components/tom-select.css';

import Alpine from 'alpinejs';
import TomSelect from 'tom-select';
import userManagement from './pages/admin/userManagement';

// Gán vào window để file Blade có thể truy cập
window.userManagement = userManagement;
window.TomSelect = TomSelect;

window.Alpine = Alpine;
Alpine.start();

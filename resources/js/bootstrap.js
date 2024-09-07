// * Importar o framework boostrap *
import 'bootstrap';

// Importar o jQuery
import jQuery from 'jquery';
window.$ = jQuery;

// Importar o Select2
import select2 from 'select2'
select2();

import axios from 'axios';
window.axios = axios;

import Swal from 'sweetalert2';
window.Swal = Swal;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

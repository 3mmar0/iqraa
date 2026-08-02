import './bootstrap';
import Alpine from 'alpinejs';
import { courseIntroUpload } from './course-intro-upload';

window.Alpine = Alpine;
Alpine.data('courseIntroUpload', courseIntroUpload);
Alpine.start();

import './bootstrap';
import Alpine from 'alpinejs';
import { courseIntroUpload } from './course-intro-upload';
import { mediaUploader } from './media-uploader';

window.Alpine = Alpine;
Alpine.data('courseIntroUpload', courseIntroUpload);
Alpine.data('mediaUploader', mediaUploader);
Alpine.start();

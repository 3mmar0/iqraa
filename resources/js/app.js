import './bootstrap';
import Alpine from 'alpinejs';
import { courseIntroUpload } from './course-intro-upload';
import { mediaUploader } from './media-uploader';
import { lessonPlayer } from './lesson-player';

window.Alpine = Alpine;
Alpine.data('courseIntroUpload', courseIntroUpload);
Alpine.data('mediaUploader', mediaUploader);
Alpine.data('lessonPlayer', lessonPlayer);
Alpine.start();

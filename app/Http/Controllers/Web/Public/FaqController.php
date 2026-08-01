<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\FaqArticle;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $articles = FaqArticle::query()
            ->where('published', true)
            ->orderBy('position')
            ->orderBy('title')
            ->get();

        if ($articles->isEmpty()) {
            $articles = collect([
                (object) [
                    'title' => 'كيف أسجّل في مقرر؟',
                    'body' => 'أنشئ حساباً، تصفّح كتالوج المقررات، ثم أرسل طلب التحاق من صفحة المقرر. يراجع الفريق الطلب ويُفعَّل وصولك عند الموافقة.',
                ],
                (object) [
                    'title' => 'هل المنصة بالعربية؟',
                    'body' => 'نعم. الواجهة والمحتوى التعليمي موجّهان باللغة العربية واتجاه الكتابة من اليمين لليسار.',
                ],
                (object) [
                    'title' => 'ماذا أجد داخل لوحة الطالب؟',
                    'body' => 'مقرراتك، الدروس، التقدم، الإشعارات، التقويم، وطلبات الدعم — كل ذلك من مكان واحد.',
                ],
                (object) [
                    'title' => 'كيف أتواصل مع الدعم؟',
                    'body' => 'استخدم صفحة «تواصل معنا» أو تذكرة الدعم بعد تسجيل الدخول كطالب.',
                ],
            ]);
        }

        return view('public.pages.faq', compact('articles'));
    }
}

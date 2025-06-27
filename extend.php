<?php

use Flarum\Extend;
use Flarum\Frontend\Document;

return [
    new Blomstra\Redis\Extend\Redis([
        'host' => 'localhost',
        'password' => "tunganh2003",
        'port' => 6379,
        'database' => 0,
    ]),
    // Inject footer vào trang forum
    (new Extend\Frontend('forum'))
        ->content(function (Document $document) {
            $year = date("Y");

            $document->foot[] = <<<HTML
<footer class="footer text-[#6B6B6B] dark:!text-[#838783] bg-white dark:!bg-[var(--main-white)] relative z-30 mt-4">
    <div class="bg-[#319527] shadow-md">
        <!-- Menu -->
        <div class="container">
            <ul class="flex justify-start items-center py-4 text-white text-[14px] gap-6">
                <li class="hidden md:block"><a href="/">Trang chủ</a></li>
                <li class="hidden md:block"><a href="/help">Trợ giúp</a></li>
                <li><a href="/">Điều khoản &amp; Quy định</a></li>
                <li><a href="/">Chính sách quyền riêng tư</a></li>
            </ul>
        </div>
    </div>

    <div class="container pt-7">
        <div>
            <div class="w-[50%] absolute h-full mb-4 top-0 right-0 -z-10 footer-bg" style="background-image: url('/assets/footer.jpg'); background-size: cover; background-position: center;">
            </div>
            <div class="fade-to-left" style="width: 50%"></div>
        </div>
        <div class="row">
            <div class="flex-1 md:!mb-0">
                <img src="/assets/logo.png" alt="Logo" class="w-[100px] mb-3">
                <h2 class="font-bold text-[1rem]">Diễn đàn học sinh Chuyên Biên Hòa</h2>
                <div class="flex items-center gap-2 !mt-3 text-[20px]">
                    <a href="https://facebook.com/cbhyouthonline" style="text-decoration: none;" target="_blank" class="rounded-full h-[35px] w-[35px] flex justify-center items-center bg-[#3b5998] text-white">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://github.com/tunnaduong/cbh-youth-online-php" style="text-decoration: none;" target="_blank" class="rounded-full h-[35px] w-[35px] flex justify-center items-center bg-black text-white">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
                <p class="text-[13px] !mt-5">Trang web hoạt động phi lợi nhuận<br>
                    <em>(không thuộc quản lý của nhà trường)</em>
                </p>
            </div>
            <div class="flex-1 mb-4 md:!mb-0">
                <h3 class="font-bold text-[16px]">Chuyên mục nổi bật</h3>
                <ul class="list-none mt-3 flex flex-col gap-2">
                    <li><a href="/forum/hoc-tap" class="hover:text-[#319527] text-[16px]">Góc học tập</a></li>
                    <li><a href="/forum/hoat-dong-ngoai-khoa/cau-lac-bo" class="hover:text-[#319527] text-[16px]">Câu
                            lạc bộ</a></li>
                    <li><a href="/forum/hoat-dong-ngoai-khoa" class="hover:text-[#319527] text-[16px]">Hoạt động</a>
                    </li>
                    <li><a href="/forum/giai-tri-xa-hoi" class="hover:text-[#319527] text-[16px]">Giải trí</a></li>
                    <li><a href="/forum/hoc-tap/ebook-giao-trinh" class="hover:text-[#319527] text-[16px]">Tài liệu
                            ôn thi</a></li>
                </ul>
            </div>
            <div class="flex-1 mb-4 md:!mb-0">
                <h3 class="font-bold text-[16px]">Chính sách</h3>
                <ul class="list-none mt-3 flex flex-col gap-2">
                    <li><a href="/Admin/posts/213054" class="hover:text-[#319527] text-[16px]">Nội quy diễn đàn</a>
                    </li>
                    <li><a href="/" class="hover:text-[#319527] text-[16px]">Chính sách bảo mật</a></li>
                    <li><a href="/" class="hover:text-[#319527] text-[16px]">Điều khoản sử dụng</a></li>
                </ul>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-[16px]">Liên hệ &amp; Hỗ trợ</h3>
                <ul class="list-none mt-3 flex flex-col gap-2">
                    <li class="text-[16px]">Email: <a href="mailto:cbhyouthonline@gmail.com" class="hover:text-[#319527]">cbhyouthonline@gmail.com</a></li>
                    <li class="text-[16px]">Hotline: <a href="tel:+84707006421" class="hover:text-[#319527]">(+84) 7070 064
                            21</a></li>
                    <li class="text-[16px]">Fanpage: <a href="https://facebook.com/cbhyouthonline" class="hover:text-[#319527]">@CBHYouthOnline</a></li>
                </ul>
            </div>
        </div>
        <div class="row text-[12px] py-3 justify-center">
            <div class="col-md-12 text-center">
                <p>© $year Công ty Cổ phần Giải pháp Giáo dục <a href="https://fatties.vn">Fatties
                        Software</a> - Được phát
                    triển bởi học sinh, dành cho học sinh.</p>
            </div>
        </div>
    </div>
</footer>
HTML;
        }),
];

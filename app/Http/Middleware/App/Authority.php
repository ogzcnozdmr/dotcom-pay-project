<?php

namespace App\Http\Middleware\App;

use App\Models\AuthorityPages;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authority
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        echo "geldi"; die();
        $user = new User();
        $illegal_select = $user->__data(session()->get('users')['id'], 'user_name,user_email,user_phone,official_distributor', [
            'user_username'  => session()->get('users')['username'],
            'user_authority' => session()->get('users')['authority']
        ]);

        if (empty($illegal_select)) {
            return redirect()->route('home.danger');
        }

        $pages_illeal = '';

        $yetki_alan_array = [1, 2, 3, 4, 5];//bütün yetkiler tanımlandı

        if (session()->get('users')['authority'] !== "admin") {//eğer yonetici değilse ve sayfa kontrolü varsa kontroller çalışacak
            $authority_pages = new AuthorityPages();
            $yetki_sayfa_get = current($authority_pages->__data(null, '*', ['authority_pages_page' => $pages_illeal]));

            if (empty($yetki_sayfa_get)) {//yetki sayfa bizim yetkilendirdiğimiz veritabanımızda yoksa
                return redirect()->route('home.danger');
            }

            $yetki_sayfa_id = $yetki_sayfa_get['authority_pages_id'];

            $authority = new \App\Models\Authority();
            $yetki_kullanici_get = current($authority->__data(null, '*', [
                'authority_name' => session()->get('users')['authority']
            ]));

            if (empty($yetki_kullanici_get)) {
                return redirect()->route('home.danger');
            }

            $yetki_alan_array = __json_decode($yetki_kullanici_get['authority_area']);//yetki alan dizimiz

            /*
             * Sayfa yetkisi yetki alanı dizimizde yoksa
             */
            if (!in_array($yetki_sayfa_id, $yetki_alan_array)) {
                if ($yetki_sayfa_id === 2) {
                    return redirect()->route('pay.screen');
                } else {
                    return redirect()->route('home.danger');
                }
            }
        }

        $yetkili_firma = session()->get('users')['authority'] === "seller" && $illegal_select['yetkili_bayi'] === '0' ? 0 : 1;
        return $next($request);
    }
}

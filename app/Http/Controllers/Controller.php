<?php

namespace App\Http\Controllers;

use App\Models\Authority;
use App\Models\AuthorityPages;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected array $result = [
        'result'   => false,
        'message'  => 'İşlem Başarısız.',
        'location' => '',
        'id'       => 0
    ];

    /**
     * @var array
     */
    public $__global = [
        /*
         * Notifications
         */
        'notifications' => [],
        /*
         * Yetkili firma
         */
        'authority_seller' => 0,
        /*
         * Yetki dizisi
         */
        'authorization_array' => []

    ];

    public function __construct()
    {
        $this->startNotifications();

        /*
         * View share update
         */
        \Illuminate\Support\Facades\View::share([
            '__global' => $this->__global
        ]);
    }

    /**
     * Bildirimleri başlatır
     * @return void
     */
    private function startNotifications() : void
    {
        $notifications = new Notifications();
        $this->__global['notifications'] = $notifications->__data(null, '*', ['notifications_read' => '0']);
    }

    /**
     * Yetkileri başlatır
     * @param string $pages_illeal
     */
    protected function startIllegal(string $pages_illeal = '') : void
    {
        $user = new User();
        $illegal_select = $user->__data(session()->get('users')['id'], 'user_name,user_email,user_phone,official_distributor', [
            'user_username'  => session()->get('users')['username'],
            'user_authority' => session()->get('users')['authority']
        ]);
        if (empty($illegal_select)) {
            __redirect('home.danger', '?error=illegalNotSelect');
        }
        /*
         * Yetki dizisi full
         */
        $this->__global['authorization_array'] = [1, 2, 3, 4, 5];
        /*
         * Yönetici değilse ve sayfa kontrolü varsa kontroller çalışacak
         */
        if (session()->get('users')['authority'] !== "admin") {
            $authority_pages = new AuthorityPages();
            $authorized_pages_get = current($authority_pages->__data(null, '*', ['authority_pages_page' => $pages_illeal]));
            /*
             * Yetkilendirdiğimiz veritabanımızda yoksa
             */
            if (empty($authorized_pages_get)) {
                __redirect('home.danger', '?error=illegalNotAuthorizedPages');
            }
            $authorized_pages_id = $authorized_pages_get['authority_pages_id'];
            $authority = new Authority();
            $authorized_user_get = current($authority->__data(null, '*', [
                'authority_name' => session()->get('users')['authority']
            ]));
            /*
             * Yetki bilgisi yoksa
             */
            if (empty($authorized_user_get)) {
                __redirect('home.danger', '?error=illegalNotAuthorizedUser');
            }
            /*
             * Yetki alan dizisi
             */
            $this->__global['authorization_array'] = __json_decode($authorized_user_get['authority_area']);
            /*
             * Sayfa yetkisi yetki alanı dizimizde yoksa
             */
            if (!in_array($authorized_pages_id, $this->__global['authorization_array'])) {
                if ($authorized_pages_id === 2) {
                    __redirect('pay.screen');
                } else {
                    __redirect('home.danger', '?error=illegalNotAuthorizedPages');
                }
            }
        }
        $this->__global['authority_seller'] = session()->get('users')['authority'] === "seller" && $illegal_select['official_distributor'] === '0' ? 0 : 1;
        /*
         * View share update
         */
        \Illuminate\Support\Facades\View::share([
            '__global' => $this->__global
        ]);
    }

    /**
     * Yetkili bayi kontrolü
     * @return void
     */
    public function starOfficialDistributor() {
        if ($this->__global['authority_seller'] === 0) {
            __redirect('home.danger', '?error=officialDistributorNotAuthorized');
        }
    }
}

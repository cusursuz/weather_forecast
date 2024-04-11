<?php

declare(strict_types=1);

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Url;

class HistoryDropdown extends Widget
{
    public $cityId;
    public $createdFrom;
    public $createdTo;

    public function run(): string
    {
        $url = Url::to(['forecast/history', 'id' => $this->cityId, 'createdFrom' => $this->createdFrom, 'createdTo' => $this->createdTo]);

        return '<div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Action
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="' . $url . '">History</a></li>
            </ul>
        </div>';
    }
}

<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use ZhuiTech\BootLaravel\Controllers\RestResponse;

class ExportController extends Controller
{
    use RestResponse;

    protected $cache;

    public function __construct()
    {
        $this->cache = cache();
    }

    public function index()
    {
        $toggle = request('toggle');
        return view('admin::export.index', compact('toggle'));
    }

    public function downLoadFile()
    {
        Storage::makeDirectory('public/exports');
        $type = request('type');
        $cache = request('cache');
        $title = explode(',', request('title'));
        $prefix = request('prefix');
        $data = $this->cache->pull($cache);
        $fileName = generate_export_name($prefix);

        if ('csv' == $type) {
            $path = export_csv($data, $title, $fileName);
            $result = \File::move($path, storage_path('app/public/exports/').$fileName.'.csv');
            if ($result) {
                return $this->success(['url' => '/storage/exports/'.$fileName.'.csv']);
            }
            return $this->fail();
        }

        set_time_limit(10000);
        ini_set('memory_limit', '300M');

        Excel::create($fileName, function ($excel) use ($data, $title) {
            $excel->sheet('Sheet1', function ($sheet) use ($data, $title) {
                $sheet->prependRow(1, $title);
                $sheet->rows($data);
            });
        })->store('xls', storage_path('exports'), false);

        $result = \File::move(storage_path('exports').'/'.$fileName.'.xls', storage_path('app/public/exports/').$fileName.'.xls');
        if ($result) {
            return $this->success(['url' => '/storage/exports/'.$fileName.'.xls']);
        }
        return $this->fail();
    }

    public function setNoteRead()
    {
        $user = auth('admin')->user();
        $type = request('type');
        $user->unreadNotifications->where('type', $type)->where('created_at', '<', Carbon::now())->markAsRead();

        return $this->success();
    }
}

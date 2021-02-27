<?php
namespace ZhuiTech\BootAdmin\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\File;
use ZhuiTech\BootAdmin\Events\LargeFileUploaded;

class SaveLargeFile implements ShouldQueue
{
	public function handle(LargeFileUploaded $event)
	{
		$resource = $event->resource;

		if ($resource->group == 'private') {
			$disk = \Storage::disk('private');
		} else {
			$disk = \Storage::disk('public');
		}

		$disk->putFileAs(dirname($resource->getPath()), new File($resource->getRealPath()), $resource->name);
		$resource->disk->delete($resource->getPath());
	}
}
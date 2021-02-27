<?php

namespace ZhuiTech\BootAdmin\Events;

use AetherUpload\Resource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LargeFileUploaded
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * @var Resource
	 */
	public $resource;

	public function __construct(Resource $resource)
	{
		$this->resource = $resource;
	}
}
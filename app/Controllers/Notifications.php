<?php
namespace App\Controllers;
use App\Models\NotificationModel;
class Notifications extends BaseController
{
 public function read(string $id){$model=new NotificationModel();$notification=$model->find((int)$id);if($notification)$model->update((int)$id,['is_read'=>1]);return redirect()->to($this->request->getGet('return')?:'/');}
 public function readAll(){(new NotificationModel())->where('is_read',0)->set(['is_read'=>1])->update();return redirect()->back();}
}

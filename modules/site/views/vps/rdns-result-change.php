<?php
use yii\helpers\Html;
use app\models\Rdnsdurum;

?>


<?= Yii::$app->session->set('username', $vps->server->username); ?>


						 <a class="btn btn-warning margin-15" onclick="rdns_create(<?=$vps->id;?>)"><i class="fas fa-plus"></i> RDNS Oluştur</a>
						 
							<table class="table">
							
								<thead>
									<tr>
										<th>Süreç</th>
										<th>Ip Adresi</th>
										<th>Type</th>
										<th>Content</th>
										<th>Priority</th>
										<th>TTL</th>
										<th>İşlem</th>
									</tr>
								</thead>
								
								<tbody>
								
									<?php
									
										foreach($rdnsWaitPendingInsert as $rdnsWaitInsert){
											
											echo "<tr id=\"waitpending-{$rdnsWaitInsert->id}\">
											<td><label class=\"label label-success\"> Eklenme Sürecinde</label></td>
											<td>{$rdnsWaitInsert->ana_ip}</td>
											<td>{$rdnsWaitInsert->type}</td>
											<td>{$rdnsWaitInsert->content}</td>
											<td>{$rdnsWaitInsert->priority}</td>
											<td>{$rdnsWaitInsert->ttl}</td>
											<td>
											<a class=\"btn btn-primary\" onclick=\"rdns_pending_edit({$rdnsWaitInsert->id})\"><i class=\"fas fa-edit\"></i></a>
											<a class=\"btn btn-danger\" onclick=\"rdns_pending_delete({$rdnsWaitInsert->id})\"><i class=\"fas fa-trash\"></i></a> 
											</td>
											</tr>";
										}
									
										foreach($rdns_datas as $rdnsdata){
											
											echo "<tr>
											<td>{$rdnsdata->surec}</td>
											<td>{$rdnsdata->ana_ip}</td>
											<td>{$rdnsdata->type}</td>
											<td>{$rdnsdata->content}</td>
											<td>{$rdnsdata->priority}</td>
											<td>{$rdnsdata->ttl}</td>
											<td>
											<a class=\"btn btn-primary\" onclick=\"rdns_edit({$rdnsdata->id})\"><i class=\"fas fa-edit\"></i></a>
											<a class=\"btn btn-danger\" onclick=\"rdns_delete({$rdnsdata->id})\"><i class=\"fas fa-trash\"></i></a> 
											</td>
											</tr>";
										}
									?>
								
								</tbody>
								
							
							</table>

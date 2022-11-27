<?php
use yii\helpers\Html;
?>

<style type="text/css">
.select-os-table{
    box-shadow:none!important;
    border:0!important;
}

.select-os-table td{
    border:0!important;
}
</style>

<table class="table select-os-table">
    <tbody>
        <tr>
            <td width="90">RAM</td>
            <td>
                <div class="progress">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo (($usedRam / $ram) * 100);?>%">
                  </div>
                </div>
                <span style="float:left;margin-top:-10px;font-size:13px;"><?php echo $usedRam;?> MB 'ta <?php echo $ram;?> MB Kullanılmış</span>
            </td>
        </tr>
        <tr>
            <td width="90">CPU</td>
            <td>
                <div class="progress">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo (($usedCpu / $cpu) * 100);?>%">
                  </div>
                </div>
                <span style="float:left;margin-top:-10px;font-size:13px;"><?php echo $usedCpu;?>MHZ 'de <?php echo $cpu;?> MHZ Kullanılmış</span>
            </td>
        </tr>
        <!--<tr>
            <td width="90">BW KULLANILDI</td>
            <td>
                <table class="table table-bordered">
                    <thead>
                        <th>1 hafta önce </th>
                        <th>1 ay önce</th>
                        <th>1 yıl önce</th>
                        <th>Tüm zamanlar</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>30 GB</td>
                            <td>45 GB</td>
                            <td>85 GB</td>
                            <td>160 GB</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>-->
        <tr>
            <td width="90">ERİŞİM</td>
            <td>
                <i class="fas fa-stopwatch"></i>&nbsp;&nbsp;<?php echo $uptime['days'];?> Gün <?php echo $uptime['hours'];?> Saat <?php echo $uptime['mins'];?> Dakika <?php echo $uptime['secs'];?> Saniye<br>
            </td>
        </tr>
    </tbody>
</table>
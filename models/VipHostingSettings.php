<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;


class SatanHostingSettings extends Model{
	
	# SatanHosting_default 
	# sabitini sadece şu işletim sistemlerinde kullanın.
	# Centos 7
	# Centos 6.8
	# Centos 8
	# Debian 8.5
	# Debian 9.6
	# Debian 9.9
	# Ubuntu 16.04
	# Ubuntu 18.04
	# Windows 2003
	# Windows 2008
	# Windows 2012
	# Windows 2019
	# Windows 10
	
	
	# Konsola SSL Eklemek için Aşağıya Sertifika ve Private Key kodlarını giriniz. 
	# Ardından autovm nin kurulu olduğu adresinizin sonuna aşağıdaki satırı ekleyin.
	# /site/vps/sslstartSatanHosting
	# Örnek : https://panel.SatanHosting.com.tr/site/vps/sslstartSatanHosting
	
	public static  $ssl_crt = "-----BEGIN CERTIFICATE-----
MIIFXDCCBESgAwIBAgISA1JXPv8Ecx95CqgrG4l2vrL5MA0GCSqGSIb3DQEBCwUA
MEoxCzAJBgNVBAYTAlVTMRYwFAYDVQQKEw1MZXQncyBFbmNyeXB0MSMwIQYDVQQD
ExpMZXQncyBFbmNyeXB0IEF1dGhvcml0eSBYMzAeFw0yMDAyMTEwMTUxNTNaFw0y
MDA1MTEwMTUxNTNaMB0xGzAZBgNVBAMTEnBhbmVsLnZob3N0LmNvbS50cjCCASIw
DQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAKf8znxhFErmj5kMUHnRmOybb9+g
0tZNqjfAb9gLkwWpi6eziBReEiXOFQ5RdKWl3p6K7QWh8FIiqiKbJVdMOqZ3w7dl
yYl8AAkiw1/MMa97nX0Bi4JndBM+tc4WdUAhbG9iARJSiTSbN1qMIvj2hKNocliU
4FKWbwy9/T1g8UUE+ljlTPub1nCjKkAR1UpcTCgia24q+sAZ4h23+p/BxmlwzOLB
S2ZATN5iy0//BkJk3cin/H82NXIud56TBzU3q57GFJOPkunPfbDpUGQ0WBQnrFfd
9EBLloS/5PoP7xNSPKSBsIbjKb9SfFqr0ft8p5yUjbSkwm6TNLIYiVjTDr0CAwEA
AaOCAmcwggJjMA4GA1UdDwEB/wQEAwIFoDAdBgNVHSUEFjAUBggrBgEFBQcDAQYI
KwYBBQUHAwIwDAYDVR0TAQH/BAIwADAdBgNVHQ4EFgQU5Zq00eUtfjtySLrtsxd/
Si5NhLswHwYDVR0jBBgwFoAUqEpqYwR93brm0Tm3pkVl7/Oo7KEwbwYIKwYBBQUH
AQEEYzBhMC4GCCsGAQUFBzABhiJodHRwOi8vb2NzcC5pbnQteDMubGV0c2VuY3J5
cHQub3JnMC8GCCsGAQUFBzAChiNodHRwOi8vY2VydC5pbnQteDMubGV0c2VuY3J5
cHQub3JnLzAdBgNVHREEFjAUghJwYW5lbC52aG9zdC5jb20udHIwTAYDVR0gBEUw
QzAIBgZngQwBAgEwNwYLKwYBBAGC3xMBAQEwKDAmBggrBgEFBQcCARYaaHR0cDov
L2Nwcy5sZXRzZW5jcnlwdC5vcmcwggEEBgorBgEEAdZ5AgQCBIH1BIHyAPAAdwBe
p3P531bA57U2SH3QSeAyepGaDIShEhKEGHWWgXFFWAAAAXAyKQMyAAAEAwBIMEYC
IQDcfjoU+DglyMkMilYq/ohC1SPfReKjZu1yLTCoIXOXqQIhALhl6W0UoYQLIqBJ
xnVoyIfcHU/gjqlWi9JH0WOWPI6+AHUAB7dcG+V9aP/xsMYdIxXHuuZXfFeUt2ru
vGE6GmnTohwAAAFwMikDgQAABAMARjBEAiAccS1PUxyjyLb9FHxK9/qDUCnvkQWz
doZVyE/YbJKM7QIgBdB38Ypuqj4agR9TkrF+EYeqCnueffTa4DOjY9bRwagwDQYJ
KoZIhvcNAQELBQADggEBAA8o2/jRsrV9Rl/+A8ugllb4xbZuqmRXS7x/70oAElbI
NkKqOV+6o2kwinPatbTTzCbhB+Bl9WaOAViX9af7v1txsowEZlHleP4jXj6CAAPY
BDm45lQ99OrPTMqJV4NqqysMiRMaYBNOH9BbrifHxH4MNPlhtGgYM81XVXKlNaqA
mnggXg1ELzM1TMgRlcN0RK9yfXcyotRw6lm6PuNocITEEiuc5KP+Y1CND1vRPUY6
n0Fk8rll6aR3ATQSazCQ3S0t944RcKEnJiQ9yUZhU4r5NUQEGxwGMhI+8GGbbduR
B1OjXQCGbfaeok7p993ifBx2700ar/iVHdzTf9PFxw4=
-----END CERTIFICATE-----";
	
	public static  $ssl_key = "-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCn/M58YRRK5o+Z
DFB50Zjsm2/foNLWTao3wG/YC5MFqYuns4gUXhIlzhUOUXSlpd6eiu0FofBSIqoi
myVXTDqmd8O3ZcmJfAAJIsNfzDGve519AYuCZ3QTPrXOFnVAIWxvYgESUok0mzda
jCL49oSjaHJYlOBSlm8Mvf09YPFFBPpY5Uz7m9ZwoypAEdVKXEwoImtuKvrAGeId
t/qfwcZpcMziwUtmQEzeYstP/wZCZN3Ip/x/NjVyLneekwc1N6uexhSTj5Lpz32w
6VBkNFgUJ6xX3fRAS5aEv+T6D+8TUjykgbCG4ym/Unxaq9H7fKeclI20pMJukzSy
GIlY0w69AgMBAAECggEAV6+llNUTw5kgAbKk20GmAnOMbP3EsCpwHJPtjadVAhKj
HFGhSYhlDqjI4uVv7vtq/fTkjhkembEQf+neDvhycOx5E+Uk0wgP+LTVqM6sbL4o
0dSZnuMjetw0CDQ+1c/cizNr/cVpazVOKoyVwMDlMWrWrRhhE5sEsnEFnCFbLzv2
JURYJ8sxgF5EbFpMseBdhswMbvfC1NzE681IFXpZEd1cACwYs2oenvdQlHLVcmEY
JKci1ybVDQaI3G23iHWv7SlbcMK3PzswH9QSMt4lKets1re/1fgjX50RKN3Ktm1C
0NYTmkpTsCJslWW5j/vR+2ZBdXFZX7ctlfcQ2EDNfQKBgQDa7zyLiMrO1wVatWrN
hVH/BKKAg65sk+uNoVBGEuhL7Ze/uuzsjVeoYW5cxPRSjEm2GhLn1SflllllcHnO
Ho5KIX1IN6+rFoWk9Dq2eMiX8Cec//sckYohlQalpbahH8KJl2LcWkPbIzETYL0J
NqM/aBOdiQsj/1p9/fWV13f1XwKBgQDEbX6ZM6GoUSvY76aQ5uE4zCvkZdWKxyaC
8E6AgymqpTpYHtOIhqzGZRF4iQaSaoMYfhZ3F2fsnWLnqsZ6reZUjycS0mF5KTtw
OyhfANA65eQYovpMgh4J+lbbcQDQ+hCKG9k8vqdy/aN1eZUcYDO1qgs3upwHqIC/
KpQ+dO21YwKBgQCpgo3II3OTTlww7UHyOoVQBBClnE8Sdjzs/bcfakg9cYoKwvnp
9U2nazh9z2iz+TahLfLxWNJ5Kj2xErLcakAVGXTc03DJ8LgtDYPgAAifyyuAnZmZ
dv4SHkZ1Pqb2fZar3XCH18OuCcNXk5EJbypaT4TQdmkPyAgSgO0CxT911QKBgGVY
8Vbuctw7dVoq3FArL810Wrkvaax+8Hnirz5Tbm4jbiUFUrNAeWirwQXl/UQSPK2o
cMLlirNkySEq1dl6XDsI2Wo2riArjAVuIHhUHRwmfTfQ70AGaqVPSv1SIs+wFlSL
5dwXqqIECBCWuL65TDTRFZSdbd/tqu/3ciF/hx5tAoGBAIc+gMSBx7Q+eK1wvhHL
tSmlJ1VcmwjjMve4IeID/amLiq+xhKEnsBYkaBHkF2I5Rh+S7J51BpqQUUSsywK+
52jRn6TnZ4gvCRxi/fxiNWL6VEg8yPGohe6XW3BYmxi/5owj76b9CoOs8kNZe+/c
p5+8Rtf5Cvlupmylj3hY+FxV
-----END PRIVATE KEY-----";
	
	
	public static function autoPassword(){
		
        $one = 'abcdefghijklmnopqrstuvwxyz';
        $two = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $three = '1234567890';

        $password = '';

        for ($i=0; $i<3; $i++) {
            $password .= $one[mt_rand(0, strlen($one)-1)];
        }

        for ($i=0; $i<3; $i++) {
            $password .= $two[mt_rand(0, strlen($two)-1)];
        }

        for ($i=0; $i<3; $i++) {
            $password .= $three[mt_rand(0, strlen($three)-1)];
        }
		
		return $password;
		
	}
	
	
	public static function networkSettings($variable, $ipDetail = false){
		
		$variables = [
			'centos7' => 'SatanHosting_default',
			'centos6_8' => 'SatanHosting_default',
			'centos8' => 'SatanHosting_default',
			'debian8_5' => 'SatanHosting_default',
			'debian9_6' => 'SatanHosting_default',
			'debian9_9' => 'SatanHosting_default',
			'ubuntu16_04' => 'SatanHosting_default',
			'ubuntu18_04' => 'SatanHosting_default',
			'ubuntu19_04' => 'SatanHosting_default',
			'windows2003' => 'SatanHosting_default',
			'windows2008' => 'SatanHosting_default',
			'windows7' => 'SatanHosting_default',
			'windows8' => 'SatanHosting_default',
			'windows2012' => 'SatanHosting_default',
			'windows2016' => 'SatanHosting_default',
			'windows2019' => 'SatanHosting_default',
			'windows10' => 'SatanHosting_default'
		];
		
		return $variables[$variable];
	}
	
	public static function isletimSistemleri(){
		# SatanHosting_default = SatanHostingun kendi ayarları
		/*
			'örnek' => 
						'os_name' => Veritabanında os isimi
						'networkSettings' => Network Ayarlaması için gereken kodlar. Array içinde yazılması gerekir. Örnek = array($networkAyar1, $networkAyar2)
						'windows' => Eklediğinz işletim sistemi windows mu ? windows ise true değil ise false
		*/
		
		// Array içindeki SatanHostingun eklediği sabitler değişemez. Değiştirildiğinde default kodlar okunmaz.
		
		return [
			'centos7' => ['os_name' => 'centos 7', 'networkSettings' => SatanHostingSettings::networkSettings('centos7'), 'windows' => false],
			'centos6_8' => ['os_name' => 'centos 6.8', 'networkSettings' => SatanHostingSettings::networkSettings('centos6_8'), 'windows' => false],
			'centos8' => ['os_name' => 'centos 8', 'networkSettings' => SatanHostingSettings::networkSettings('centos8'), 'windows' => false],
			'debian8_5' => ['os_name' => 'debian 8.5', 'networkSettings' => SatanHostingSettings::networkSettings('debian8_5'), 'windows' => false],
			'debian9_6' => ['os_name' => 'debian 9.6', 'networkSettings' => SatanHostingSettings::networkSettings('debian9_6'), 'windows' => false],
			'debian9_9' => ['os_name' => 'debian 9.9', 'networkSettings' => SatanHostingSettings::networkSettings('debian9_9'), 'windows' => false],
			'ubuntu16_04' => ['os_name' => 'ubuntu 16.04', 'networkSettings' => SatanHostingSettings::networkSettings('ubuntu16_04'), 'windows' => false],
			'ubuntu18_04' => ['os_name' => 'ubuntu 18.04', 'networkSettings' => SatanHostingSettings::networkSettings('ubuntu18_04'), 'windows' => false],
			'ubuntu19_04' => ['os_name' => 'ubuntu 19.04', 'networkSettings' => SatanHostingSettings::networkSettings('ubuntu19_04'), 'windows' => false],
			'windows2003' => ['os_name' => 'windows 2003', 'networkSettings' => SatanHostingSettings::networkSettings('windows2003'), 'windows' => true],
			'windows2008' => ['os_name' => 'windows server 2008', 'networkSettings' => SatanHostingSettings::networkSettings('windows2008'), 'windows' => true],
			'windows7' => ['os_name' => 'windows 7', 'networkSettings' => SatanHostingSettings::networkSettings('windows2008'), 'windows' => true],
			'windows8' => ['os_name' => 'windows 8', 'networkSettings' => SatanHostingSettings::networkSettings('windows2008'), 'windows' => true],
			'windows2012' => ['os_name' => 'windows server 2012', 'networkSettings' => SatanHostingSettings::networkSettings('windows2012'), 'windows' => true],
			'windows2016' => ['os_name' => 'windows server 2016', 'networkSettings' => SatanHostingSettings::networkSettings('windows2012'), 'windows' => true],
			'windows2019' => ['os_name' => 'windows server 2019', 'networkSettings' => SatanHostingSettings::networkSettings('windows2019'), 'windows' => true],
			'windows10' => ['os_name' => 'windows 10', 'networkSettings' => SatanHostingSettings::networkSettings('windows10'), 'windows' => true]
			
			
		];
	}
	
}

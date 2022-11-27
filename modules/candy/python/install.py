import os
import time
from netaddr import IPAddress
from pysphere.resources import VimService_services as VI
from server import get_server, get_center, get_ssh, get_arg, response, append, space, path, write, delete

# Log
delete('log')

# Status
write('status', '1:5')

# Connect to server
serve = get_center()

if not serve:
  serve = get_server()
  
if not serve:
  response(False, log=10)

# Status
write('status', '1:15')
  
# Address
address = get_arg('ip[ip]')
  
# Operating system
os_name = get_arg('os[operation_system]')
os_type = get_arg('os[type]')
os_guest = get_arg('os[guest]')

network_ayarlari = get_arg('networkayari')

if not os_guest:
  os_guest = 'debian'
  
plan_network = get_arg('plan[network]')

ip_network = get_arg('ip[network]')

use_first_network = False


if ip_network == '3':
  if not plan_network:
    if ip_network == '1':
      use_first_network = True
  else:
    if plan_network == '1':
      use_first_network = True
    elif plan_network == '2':
      use_first_network = False
    else:
      use_first_network = True
else:
  if ip_network == '1':
    use_first_network = True
   
network = 'VM Network'

if get_arg('server[network]'):
  
  if use_first_network:
    network = get_arg('server[network]')
    
if get_arg('server[second_network]'):
  
  if not use_first_network:
    network = get_arg('server[second_network]')
    
# Version
version = get_arg('server[version]')

if not version:
  version = '8'
  
# Base directory
base = os.path.dirname(os.path.realpath(__file__))
  
# Template
with open(path(base, 'template')) as file:
  template = file.read()
  
data = {'ethernet0.networkName': network, 'virtualHw.version': version, 'guestOs': os_guest}

# CPU cores
cpu_cores = get_arg('plan[cpu_core]')

if cpu_cores:
  data.update({'numvcpus': cpu_cores})
  
# CPU MHZ
cpu_mhz = get_arg('plan[cpu_mhz]')

if cpu_mhz:
  data.update({'sched.cpu.max': cpu_mhz})
  
# Memory
memory = get_arg('plan[ram]')

if memory:
  data.update({'memSize': memory, 'sched.mem.max': memory})
  
# Name
data.update({'displayName': address})

# CPU cores
cpu_cores = get_arg('vps[vps_cpu_core]')

if cpu_cores:
  data.update({'numvcpus': cpu_cores})
  
# CPU MHZ
cpu_mhz = get_arg('vps[vps_cpu_mhz]')

if cpu_mhz:
  data.update({'sched.cpu.max': cpu_mhz})
  
# Memory
memory = get_arg('vps[vps_ram]')

if memory:
  data.update({'memSize': memory, 'sched.mem.max': memory})
  
# MAC address
mac_address = get_arg('ip[mac_address]')

if mac_address:
  data.update({'ethernet0.address': mac_address, 'ethernet0.addressType': 'static'})
else:
  data.update({'ethernet0.addressType': 'generated'})
  
# Network adapter
adapter = get_arg('os[adapter]')

if adapter:
  data.update({'ethernet0.virtualDev': adapter})
else:
  data.update({'ethernet0.virtualDev': 'e1000'})

# SCSI configuration
scsi = 'pvscsi'

if 'centos' in os_name:
  scsi = 'lsilogic'
  
if 'windows' in os_name:
  
  if '2003' in os_name:
    scsi = 'lsilogic'
  else:
    scsi = 'lsisas1068'
    
data.update({'scsi0.virtualDev': scsi})
  
# Prepare template
for arg in data:
  
  template = append(template, '\n', space(arg, '='), '"', data[arg], '"')

# Status
write('status', '2:25')
  
def delete(serve, machine):
  
  request = VI.Destroy_TaskRequestMsg()

  _this = request.new__this(machine._mor)
  _this.set_attribute_type(machine._mor.get_attribute_type())

  request.set_element__this(_this)

  try:
    serve._proxy.Destroy_Task(request)
  except:
    response(False, log=55)
  
# Find machine
machine = None

try:
  machine = serve.get_vm_by_name(address)
except:
  pass
  
# Delete machine
if machine:
  try:
    online = machine.is_powered_on()
  except:
    response(False, log=51)
    
  if online:
    try:
      machine.power_off()
    except:
      response(False, log=52)
      
    time.sleep(5)
      
  delete(serve, machine)
    
# Datastore
datastore = get_arg('datastore[value]')

write('status2', datastore)

# Connect to SSH
ssh = get_ssh()

if not ssh:
  response(False, log=11)
  
# Status
write('status', '2:35')

# Delete machine
commands = [['sh', '-c', space('vmkfstools --deletevirtualdisk', path('/vmfs/volumes', datastore, address, 'template.vmdk'))], ['sh', '-c', space('vmkfstools --deletevirtualdisk', path('/vmfs/volumes', datastore, address, 'template-flat.vmdk'))]]

for command in commands:
  try:
    ssh.run(command)
  except:
    continue
    
  time.sleep(5)
    
# Create directory
command = space('mkdir -p', path('/vmfs/volumes', datastore, address))

use_vsan = False

if get_arg('datastore[vsan]') == '2':
  use_vsan = True

if use_vsan:
  command = space('/usr/lib/vmware/osfs/bin/osfs-mkdir', path('/vmfs/volumes', datastore, address))

try:
  ssh.run(['sh', '-c', command])
except:
  response(False, log=20)

# Status
write('status', '2:45')
  
# Upload VMX
vmx_path = path('/vmfs/volumes', datastore, address, 'template.vmx')

try:
  file = ssh.open(vmx_path, 'w')
except:
  response(False, log=21)
  
try:
  file.write(unicode(template))
except:
  response(False, log=22)
  
file.close()

# Status
write('status', '3:55')

# Copy template
first_path = path('/vmfs/volumes/datastore-3', os_type, append(os_type, '.vmdk'))

if get_arg('datastore[vsan]') == '2':
  first_path = first_path.replace('datastore', 'vsanDatastore')
  
second_path = path('/vmfs/volumes', datastore, address, 'template.vmdk')

# Disk type
disk = 'thin'

if get_arg('vps[disk]') == 'think':
  disk = 'zeroedthick'

if get_arg('vps[disk]') == 'think x':
  disk = 'eagerzeroedthick'
  
# Create machine
hard = get_arg('plan[hard]')

if get_arg('vps[vps_hard]'):
  hard = get_arg('vps[vps_hard]')

commands = [['sh', '-c', space('vmkfstools -i', first_path, '-d', disk, second_path)], ['sh', '-c', space('vmkfstools -X', append(hard, 'G'), path('/vmfs/volumes', datastore, address, 'template.vmdk'))], ['sh', '-c', space('vim-cmd', 'solo/registervm', path('/vmfs/volumes', datastore, address, 'template.vmx'))]]

for command in commands:
  try:
    ssh.run(command)
  except:
    response(False, log=30)
    
  time.sleep(10)
    
# Status
write('status', '3:65')
    
# Find machine
try:
  machine = serve.get_vm_by_name(address)
except:
  response(False, log=50)
  
# Start machine
try:
  machine.power_on()
except:
  response(False, log=53)
  
time.sleep(5)
  
# Status
write('status', '4:75')
  
# Login to guest
try:
  machine.wait_for_tools(timeout=2048)
except:
  response(False, log=60)
  
os_username = get_arg('os[username]')
os_password = get_arg('os[password]')

try:
  machine.login_in_guest(os_username, os_password)
except:
  response(False, log=61)
  
# Status
write('status', '4:85')
  
# Gateway
gateway = get_arg('ip[gateway]')

# Netmask
netmask = get_arg('ip[netmask]')

# DHCP
dhcp = False

if get_arg('ip[is_dhcp]') == '1':
  dhcp = True
  
# Extend
extend = True

# Password
password = get_arg('vps[password]')
  
# DNS
dns1 = get_arg('server[dns1]')

if not dns1:
  dns1 = '8.8.8.8'
  
dns2 = get_arg('server[dns2]')

if not dns2:
  dns2 = '8.8.4.4'
  
# Subnet
subnet = IPAddress(netmask).netmask_bits()
  
  
# Debian 8.5
if 'debian 8.5' in os_name:
  
  if not dhcp:
    try:
      eval(network_ayarlari)
    except:
      response(False, log=62)
      
    time.sleep(4)
        
    try:
      machine.start_process('/bin/sh', args=['/home/autovm.sh', address, gateway, netmask, dns1, dns2])
    except:
      response(False, log=63)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['-c', '/etc/init.d/networking restart'])
    except:
      response(False, log=64)
      
    time.sleep(4)

# Debian 9.6
if 'debian 9.6' in os_name:
  
  if not dhcp:
    
    try:
      eval(network_ayarlari)
    except:
      response(False, log=62)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['/home/autovm.sh', address, gateway, netmask, dns1, dns2])
    except:
      response(False, log=63)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['-c', '/etc/init.d/networking restart'])
    except:
      response(False, log=64)
      
    time.sleep(4)
      
# Debian 9.9 and 10
if 'debian 9.9' in os_name or 'debian 10' in os_name:
  
  if not dhcp:
    
    try:
      eval(network_ayarlari)
    except:
      response(False, log=62)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['/home/autovm.sh', address, gateway, netmask, dns1, dns2])
    except:
      response(False, log=63)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['-c', '/etc/init.d/networking restart'])
    except:
      response(False, log=64)
      
    time.sleep(4)

# Ubuntu 16
if 'ubuntu 16.04' in os_name:
  
  if not dhcp:
    try:
      eval(network_ayarlari)
    except:
      response(False, log=62)
      
    time.sleep(4)
        
    try:
      machine.start_process('/bin/sh', args=['/home/autovm.sh', address, gateway, netmask, dns1, dns2])
    except:
      response(False, log=63)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['-c', '/etc/init.d/networking restart'])
    except:
      response(False, log=64)
      
    time.sleep(4)
      
# Ubuntu 18 and 19
if 'ubuntu 18.04' in os_name or 'ubuntu 19.04' in os_name:
  
  if not dhcp:
    try:
      eval(network_ayarlari)
    except:
      response(False, log=62)
      
    time.sleep(4)
    
    try:
      machine.start_process('/bin/sh', args=['/home/autovm.sh', address, gateway, str(subnet), dns1, dns2])
    except:
      response(False, log=63)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['-c', 'netplan apply'])
    except:
      response(False, log=64)
      
    time.sleep(4)
    
# Centos 6.8
if 'centos 6.8' in os_name:
  
  if not dhcp:
    
    try:
      eval(network_ayarlari)
    except:
      response(False, log=62)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['/home/autovm.sh', address, gateway, netmask, dns1, dns2])
    except:
      response(False, log=63)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['-c', 'service network restart']);
    except:
      response(False, log=64)
      
    time.sleep(4)
         
# Centos 7
if 'centos 7' in os_name:
  
  if not dhcp:
    
    try:
      machine.start_process('/bin/sh', args=['-c', "echo 'name=\$(ls /sys/class/net | head -n 1)\necho \"DEVICE=\$name\nTYPE=Ethernet\nONBOOT=yes\nIPADDR=\$1\nGATEWAY=\$2\nNETMASK=\$3\nDNS1=\$4\nDNS2=\$5\" > /etc/sysconfig/network-scripts/ifcfg-\$name\necho \"\$2 dev \$name\ndefault via \$2 dev \$name\" > /etc/sysconfig/network-scripts/route-\$name' > /home/autovm.sh"])
    except:
      response(False, log=62)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['/home/autovm.sh', address, gateway, netmask, dns1, dns2])
    except:
      response(False, log=63)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['-c', 'service network restart']);
    except:
      response(False, log=64)
      
    time.sleep(4)
       
# Centos 8
if 'centos 8' in os_name:
  
  if not dhcp:
    
    try:
      eval(network_ayarlari)
    except:
      response(False, log=62)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['/home/autovm.sh', address, gateway, netmask, dns1, dns2])
    except:
      response(False, log=63)
      
    time.sleep(4)
      
    try:
      machine.start_process('/bin/sh', args=['-c', 'systemctl restart NetworkManager'])
    except:
      response(False, log=64)
      
    time.sleep(4)
      
# Change password
if 'windows' not in os_name and 'mikrotik' not in os_name:
  try:
    machine.start_process('/bin/sh', args=['-c', 'echo "root:{}" | chpasswd'.format(password) ])
  except:
    response(False, log=65)

# Execute command
def guest_command(serve, machine, program, command):

  request = VI.StartProgramInGuestRequestMsg()

  _this = request.new__this(machine._proc_mgr)
  _this.set_attribute_type(machine._proc_mgr.get_attribute_type())

  request.set_element__this(_this)

  vm = request.new_vm(machine._mor)
  vm.set_attribute_type(machine._mor.get_attribute_type())

  request.set_element_vm(vm)

  request.set_element_auth(machine._auth_obj)

  spec = request.new_spec()

  spec.set_element_programPath(program)
  spec.set_element_arguments(command)

  request.set_element_spec(spec)

  result = serve._proxy.StartProgramInGuest(request)._returnval

# Windows 2003
if 'windows 2003' in os_name:
  
  if not dhcp:
    
    try:
      guest_command(serve, machine, 'cmd.exe', '/c echo for /f "skip=3 tokens=3*" %%a in (\'netsh int show int\') do netsh int ip set address name="%%b" static {} {} {} 1 > C:\\autovm.bat'.format(address, netmask, gateway))
    except:
      response(False, log=62)
      
    time.sleep(4)
      
    try:
      guest_command(serve, machine, 'cmd.exe', '/c C:\\autovm.bat')
    except:
      response(False, log=63)
      
    time.sleep(10)
    
    try:
      guest_command(serve, machine, 'cmd.exe', '/c echo for /f "skip=3 tokens=3*" %%a in (\'netsh int show int\') do netsh int ip set dns name="%%b" static {} > C:\\autovm.bat'.format(dns1))
    except:
      response(False, 62)
      
    time.sleep(4)
    
    try:
      guest_command(serve, machine, 'cmd.exe', '/c echo for /f "skip=3 tokens=3*" %%a in (\'netsh int show int\') do netsh int ip add dns name="%%b" static {} > C:\\autovm.bat'.format(dns2))
    except:
      response(False, 62)
      
    time.sleep(4)
    
    try:
      guest_command(serve, machine, 'cmd.exe', '/c C:\\autovm.bat')
    except:
      response(False, 63)
      
    time.sleep(4)
      
    if mac_address:
      
      try:
        guest_command(serve, machine, 'cmd.exe', '/c reg import C:\\ovh.reg')
      except:
        response(False, log=63)
        
      time.sleep(4)

# Windows 2008
if 'windows 2008' in os_name or 'windows 7' in os_name:
  
  if not dhcp:
    
    try:
      eval(network_ayarlari)
    except:
      response(False, log=62)
      
    time.sleep(4)
      
    try:
      guest_command(serve, machine, 'cmd.exe', '/c C:\\autovm.bat')
    except:
      response(False, log=63)
      
    time.sleep(10)
    
    try:
      guest_command(serve, machine, 'cmd.exe', '/c echo for /f "skip=3 tokens=3*" %%a in (\'netsh int show int\') do netsh int ip set dns name="%%b" static {} > C:\\autovm.bat'.format(dns1))
    except:
      response(False, 62)
      
    try:
      guest_command(serve, machine, 'cmd.exe', '/c C:\\autovm.bat')
    except:
      response(False, 63)
      
    time.sleep(4)

    try:
      guest_command(serve, machine, 'cmd.exe', '/c echo for /f "skip=3 tokens=3*" %%a in (\'netsh int show int\') do netsh int ip add dns name="%%b" {} index=2 > C:\\autovm.bat'.format(dns2))
    except:
      response(False, 62)
      
    time.sleep(4)
      
    try:
      guest_command(serve, machine, 'cmd.exe', '/c C:\\autovm.bat')
    except:
      response(False, 63)
      
    time.sleep(4)

# Windows 2012 and 2016 and 8
if 'windows 2012' in os_name or 'windows 2016' in os_name or 'windows 8' in os_name:
  
  if not dhcp:
    
    try:
      eval(network_ayarlari)
    except:
      response(False, log=62)
      
    time.sleep(4)
      
    try:
       guest_command(serve, machine, 'cmd.exe', '/c C:\\autovm.bat')
    except:
      response(False, log=63)
      
    time.sleep(10)
    
    try:
      guest_command(serve, machine, 'cmd.exe', '/c echo for /f "skip=3 tokens=3*" %%a in (\'netsh int show int\') do netsh int ip set dns name="%%b" static {} > C:\\autovm.bat'.format(dns1))
    except:
      response(False, 62)
      
    try:
      guest_command(serve, machine, 'cmd.exe', '/c C:\\autovm.bat')
    except:
      response(False, 63)
      
    time.sleep(4)

    try:
      guest_command(serve, machine, 'cmd.exe', '/c echo for /f "skip=3 tokens=3*" %%a in (\'netsh int show int\') do netsh int ip add dns name="%%b" {} index=2 > C:\\autovm.bat'.format(dns2))
    except:
      response(False, 62)
      
    time.sleep(4)
    
    try:
      guest_command(serve, machine, 'cmd.exe', '/c C:\\autovm.bat')
    except:
      response(False, 63)
      
    time.sleep(4)
      
# Windows 2019
if 'windows 2019' in os_name or 'windows 10' in os_name:
  
  if not dhcp:
    
    try:
      eval(network_ayarlari)
    except:
      response(False, log=62)
      
    time.sleep(4)
      
    try:
      guest_command(serve, machine, 'cmd.exe', '/c C:\\autovm.bat')
    except:
      response(False, log=63)
      
    time.sleep(10)
    
    try:
      guest_command(serve, machine, 'cmd.exe', '/c echo for /f "skip=3 tokens=3*" %%a in (\'netsh int show int\') do netsh int ip set dns name="%%b" static {} > C:\\autovm.bat'.format(dns1))
    except:
      response(False, 62)
      
    try:
      guest_command(serve, machine, 'cmd.exe', '/c C:\\autovm.bat')
    except:
      response(False, 63)
      
    time.sleep(4)

    try:
      guest_command(serve, machine, 'cmd.exe', '/c echo for /f "skip=3 tokens=3*" %%a in (\'netsh int show int\') do netsh int ip add dns name="%%b" {} index=2 > C:\\autovm.bat'.format(dns2))
    except:
      response(False, 62)
      
    time.sleep(4)
      
    try:
      guest_command(serve, machine, 'cmd.exe', '/c C:\\autovm.bat')
    except:
      response(False, 63)
      
    time.sleep(4)
       
# Change password
if 'windows' in os_name:
  try:
    guest_command(serve, machine, 'cmd.exe', '/c net user administrator {}\r'.format(password))
  except:
    response(False, log=65)
    
# Status
write('status', '4:90')

pingtest_limit = 30
while 0 <= pingtest_limit:
    gelencevap = os.system("ping -c 1 " + address)
    if gelencevap == 0:
        write('status', '5:100')
        break
    else:
        time.sleep(1)  
        
response(True)  
  
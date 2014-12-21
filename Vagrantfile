# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

  config.vm.box = "zsim0n/dev-box-ng"
  config.vm.hostname = "jyskebank.dev"
  config.vm.network "private_network", ip: "172.16.16.24"
  config.vm.synced_folder '.', '/vagrant', type: 'nfs',  mount_options: ['rw', 'vers=3', 'tcp', 'fsc' ,'actimeo=2']
  config.vm.provider "virtualbox" do |v|
    v.customize ["modifyvm", :id, "--memory", 1024]
    v.customize ["modifyvm", :id, "--cpus", 1]
  end

#  config.vm.provision :shell, :path => 'shell/bootstrap.sh'

  config.ssh.forward_agent = true
end

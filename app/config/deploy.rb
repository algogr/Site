# deploy.rb

set   :application,   "Site"
set   :deploy_to,     "/var/www/Site"
set   :domain,        "www.algo.gr"

set   :scm,           :git
# set   :repository,    "/home/jim/workspace/Site"
set   :repository,    "https://github.com/algogr/Site.git"
# set   :deploy_via,    :copy
# set   :deploy_via, :rsync_with_remote_cache
# set   :deploy_via,       :capifony_copy_local


role  :web,           domain
role  :app,           domain, :primary => true

set   :use_sudo,      false
set   :keep_releases, 3

set :use_composer, true
set :update_vendors, true
# set   :use_composer_tmp, true

unless ENV['_DEBUG'].nil?
    puts "Ruby Version                      => #{RUBY_VERSION}-p#{RUBY_PATCHLEVEL}"
    puts "OpenSSL::Version                  => #{OpenSSL::OPENSSL_VERSION}"
    puts "Net::SSH::Version::CURRENT        => #{Net::SSH::Version::CURRENT}"
    puts "Net::SSH -> Local platform        => #{Net::SSH::Authentication::PLATFORM}"
    puts "Remote Whoami                     => #{capture 'whoami'}"
    puts "umask on Server                   => #{capture 'umask'}"
    puts "$SHELL                            => #{capture 'echo $SHELL'}"
    puts "$BASH_VERSION                     => #{capture 'echo $BASH_VERSION'}"
    puts "Interactive Shell - Test: $PS1    => #{capture 'if [ -z "$PS1" ]; then echo no; else echo yes; fi'}"

    logger.level =          Logger::MAX_LEVEL
    ssh_options[:verbose] = :debug 
end

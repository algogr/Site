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

set :shared_children, [app_path + "/logs", web_path + "/uploads"]

# Symfony2 2.0.x
before "symfony:vendors:install", "symfony:copy_vendors"

# Symfony2 2.1
before 'symfony:composer:update', 'symfony:copy_vendors'

namespace :symfony do
  desc "Copy vendors from previous release"
  task :copy_vendors, :except => { :no_release => true } do
    if Capistrano::CLI.ui.agree("Do you want to copy last release vendor dir then do composer install ?: (y/N)")
      capifony_pretty_print "--> Copying vendors from previous release"

      run "cp -a #{previous_release}/vendor #{latest_release}/"
      capifony_puts_ok
    end
  end
end


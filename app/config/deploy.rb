# deploy.rb

set   :application,   "Site"
set   :deploy_to,     "/var/www/Site"
set   :domain,        "www.algo.gr"

set   :scm,           :git
set   :repository,    "/home/jim/workspace/Site"
# set   :deploy_via,    :copy
set   :deploy_via, :rsync_with_remote_cache


role  :web,           domain
role  :app,           domain, :primary => true

set   :use_sudo,      false
set   :keep_releases, 3

set :use_composer, true
set :update_vendors, true


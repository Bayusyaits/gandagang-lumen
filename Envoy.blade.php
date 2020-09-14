@servers(['web' => 'gandagang@157.245.49.178'])

@setup
    $branch = 'master';
    $repository = 'git@gitlab-gandagang.com:gandagang/gandagang-lumen.git';
    $app_dir = '/var/www/gandagang.com';
    $release = 'releases';
    $current = 'gandagang-lumen';
    $new_release_dir = $app_dir .'/'. $release;
@endsetup

@story('deploy')
    clean_old_release
    clone_repository
    run_composer
    update_symlinks
    deploy
@endstory

@task('clean_old_release', ['on' => 'web'])
    # Delete all but the 5 most recent.
    cd {{ $app_dir }}
    rm -rf {{ $release }}
@endtask

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $app_dir }} ] || mkdir {{ $app_dir }}
    git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('run_composer')
    echo "Starting deployment ({{ $release }})"
    cd {{ $new_release_dir }}/src
    composer install --optimize-autoloader --no-dev
@endtask

@task('update_symlinks')
    echo "Linking storage directory"
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir.'/'.$current }}/src/storage {{ $new_release_dir }}/src/storage

    echo 'Linking .env file'
    ln -nfs {{ $app_dir.'/'.$current }}/src/.env {{ $new_release_dir }}/src/.env    
@endtask

@task('deploy', ['on' => 'web', 'confirm' => true])
    cd {{ $new_release_dir }}
    git pull origin {{ $branch }}
    chown -R $USER:www-data storage
    chmod -R 775 storage
    php artisan cache:clear
@endtask

@finished
    @slack('https://hooks.slack.com/services/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', '#gandagang_be_envoy')
@endfinished


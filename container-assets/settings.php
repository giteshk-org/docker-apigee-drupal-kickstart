<?php
// Copyright 2021 Google LLC
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

use Google\Cloud\Storage\StorageClient;

$storage = new StorageClient();
$storage->registerStreamWrapper();

$databases['default']['default'] = [
    'database' => getenv('DRUPAL_DATABASE_NAME'),
    'username' => getenv('DRUPAL_DATABASE_USER'),
    'password' => getenv('DRUPAL_DATABASE_PASSWORD'),
    'host' => getenv('DRUPAL_DATABASE_HOST'),
    'port' => getenv('DRUPAL_DATABASE_PORT'),
    'driver' => getenv('DRUPAL_DATABASE_DRIVER'),
];

$settings['update_free_access'] = FALSE;
$settings['allow_authorize_operations'] = FALSE;
$settings['file_public_path'] = getenv('DRUPAL_PUBLIC_FILE_PATH') ?? 'sites/default/files';
$settings['file_private_path'] = getenv('DRUPAL_PRIVATE_FILE_PATH') ?? '/app/code/web/sites/default/private';
$settings['config_sync_directory'] = $settings['file_private_path'] . "/config";
$settings['file_temp_path'] = '/tmp';
$settings['skip_permissions_hardening'] = TRUE;

$salt_file = $settings['file_private_path'] . "/salt.txt";
if (!file_exists($salt_file)) {
    file_put_contents($salt_file, \Drupal\Component\Utility\Crypt::randomBytesBase64(55));
}
$settings['hash_salt'] = file_get_contents($salt_file);

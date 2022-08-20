<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$config = require_once('./config.php');

$tenantId = $config['tenantId'];
$clientId = $config['clientId'];
$clientSecret = $config['clientSecret'];

$skuIds = [
    'c42b9cae-ea4f-4ab7-9717-81576235ccac' => ['skuPartNumber' => 'DEVELOPERPACK_E5', 'name' => 'E5 开发者'],
    'f5a9147f-b4f8-4924-a9f0-8fadaac4982f' => ['skuPartNumber' => 'ENTERPRISEPACK_EDULRG', 'name' => 'E3 远程教育'],
    'e4fa3838-3d01-42df-aa28-5e0a4c68604b' => ['skuPartNumber' => 'ENTERPRISEPACK_FACULTY', 'name' => 'E3 教职员工'],
    '8fc2205d-4e51-4401-97f0-5c89ef1aafbb' => ['skuPartNumber' => 'ENTERPRISEPACK_STUDENT', 'name' => 'E3 学生'],
    '98b6e773-24d4-4c0d-a968-6e787a1f8204' => ['skuPartNumber' => 'ENTERPRISEPACKPLUS_STUDENT', 'name' => 'A3 学生（O365）'],
    '476aad1e-7a7f-473c-9d20-35665a5cbd4f' => ['skuPartNumber' => 'ENTERPRISEPACKPLUS_STUUSEBNFT', 'name' => 'A3 学生使用权益'],
    'a4585165-0533-458a-97e3-c400570268c4' => ['skuPartNumber' => 'ENTERPRISEPREMIUM_FACULTY', 'name' => 'E5 教职员工'],
    '9a320620-ca3d-4705-a79d-27c135c96e05' => ['skuPartNumber' => 'ENTERPRISEPREMIUM_NOPSTNCONF_FACULTY', 'name' => 'E5 教职员工（不含音频会议）'],
    '1164451b-e2e5-4c9e-8fa6-e5122d90dbdc' => ['skuPartNumber' => 'ENTERPRISEPREMIUM_NOPSTNCONF_STUDENT', 'name' => 'E5 学生（不含音频会议）'],
    'bc86c9cd-3058-43ba-9972-141678675ac1' => ['skuPartNumber' => 'ENTERPRISEPREMIUM_NOPSTNCONF_STUUSEBNFT', 'name' => 'A5 学生音频会议'],
    'ee656612-49fa-43e5-b67e-cb1fdf7699df' => ['skuPartNumber' => 'ENTERPRISEPREMIUM_STUDENT', 'name' => 'E5 学生'],
    'f6e603f1-1a6d-4d32-a730-34b809cb9731' => ['skuPartNumber' => 'ENTERPRISEPREMIUM_STUUSEBNFT', 'name' => 'A5 学生权益（O365）'],
    '16732e85-c0e3-438e-a82f-71f39cbe2acb' => ['skuPartNumber' => 'ENTERPRISEWITHSCAL_FACULTY', 'name' => 'E4 教职员工'],
    '05e8cabf-68b5-480f-a930-2143d472d959' => ['skuPartNumber' => 'ENTERPRISEWITHSCAL_STUDENT', 'name' => 'E4 学生'],
    'f30db892-07e9-47e9-837c-80727f46fd3d' => ['skuPartNumber' => 'FLOW_FREE', 'name' => 'FLOW_FREE'],
    '4b590615-0888-425a-a965-b3bf7789848d' => ['skuPartNumber' => 'M365EDU_A3_FACULTY', 'name' => 'A3 教职员工'],
    '7cfd9a2b-e110-4c39-bf20-c6a3f36a3121' => ['skuPartNumber' => 'M365EDU_A3_STUDENT', 'name' => 'A3 学生（MS365）'],
    '18250162-5d87-4436-a834-d795c15c80f3' => ['skuPartNumber' => 'M365EDU_A3_STUUSEBNFT', 'name' => 'A3 学生权益'],
    'e97c048c-37a4-45fb-ab50-922fbf07a370' => ['skuPartNumber' => 'M365EDU_A5_FACULTY', 'name' => 'A5 教职员工'],
    'e578b273-6db4-4691-bba0-8d691f4da603' => ['skuPartNumber' => 'M365EDU_A5_NOPSTNCONF_FACULTY', 'name' => 'A5 教职员工（不含音频会议）'],
    'a25c01ce-bab1-47e9-a6d0-ebe939b99ff9' => ['skuPartNumber' => 'M365EDU_A5_NOPSTNCONF_STUDENT', 'name' => 'A5 学生（不含音频会议）'],
    '81441ae1-0b31-4185-a6c0-32b6b84d419f' => ['skuPartNumber' => 'M365EDU_A5_NOPSTNCONF_STUUSEBNFT', 'name' => 'A5 学生权益（不含音频会议）'],
    '46c119d4-0379-4a9d-85e4-97c66d3f909e' => ['skuPartNumber' => 'M365EDU_A5_STUDENT', 'name' => 'A5 学生'],
    '31d57bc7-3a05-4867-ab53-97a17835a411' => ['skuPartNumber' => 'M365EDU_A5_STUUSEBNFT', 'name' => 'A5 学生权益（MS365）'],
    '3b555118-da6a-4418-894f-7df1e2096870' => ['skuPartNumber' => 'O365_BUSINESS_ESSENTIALS', 'name' => 'MS365商业基础工程反馈计划'],
    'a403ebcc-fae0-4ca2-8c8c-7a907fd6c235' => ['skuPartNumber' => 'POWER_BI_STANDARD', 'name' => 'POWER_BI_STANDARD'],
    '8c4ce438-32a7-4ac5-91a6-e22ae08d9c8b' => ['skuPartNumber' => 'RIGHTSMANAGEMENT_ADHOC', 'name' => '权益管理（手动）'],
    'a19037fc-48b4-4d57-b079-ce44b7832473' => ['skuPartNumber' => 'STANDARDPACK_FACULTY', 'name' => 'E1 教职员工'],
    'd37ba356-38c5-4c82-90da-3d714f72a382' => ['skuPartNumber' => 'STANDARDPACK_STUDENT', 'name' => 'E1 学生'],
    '94763226-9b3c-4e75-a931-5c89701abe66' => ['skuPartNumber' => 'STANDARDWOFFPACK_FACULTY', 'name' => 'A1 教职员工'],
    'af4e28de-6b52-4fd3-a5f4-6bf708a304d3' => ['skuPartNumber' => 'STANDARDWOFFPACK_FACULTY_DEVICE', 'name' => 'A1 教职员工 设备'],
    '43e691ad-1491-4e8c-8dc9-da6b8262c03b' => ['skuPartNumber' => 'STANDARDWOFFPACK_HOMESCHOOL_FAC', 'name' => '自学教育 教职员工'],
    'afbb89a7-db5f-45fb-8af0-1bc5c5015709' => ['skuPartNumber' => 'STANDARDWOFFPACK_HOMESCHOOL_STU', 'name' => '自学教育 学生'],
    '78e66a63-337a-4a9a-8959-41c6654dfb56' => ['skuPartNumber' => 'STANDARDWOFFPACK_IW_FACULTY', 'name' => 'A1P 教职员工'],
    '314c4481-f395-4525-be8b-2ec4bb1e9d91' => ['skuPartNumber' => 'STANDARDWOFFPACK_STUDENT', 'name' => 'A1 学生'],
    'e82ae690-a2d5-4d76-8d30-7c6e01e6022e' => ['skuPartNumber' => 'STANDARDWOFFPACK_IW_STUDENT', 'name' => 'A1P 学生'],
    '160d609e-ab08-4fce-bc1c-ea13321942ac' => ['skuPartNumber' => 'STANDARDWOFFPACK_STUDENT_DEVICE', 'name' => 'A1 学生 设备']
];

$roleIds = [
    '62e90394-69f5-4237-9190-012177145e10' => 'Global Administrator',
    'fe930be7-5e62-47db-91af-98c3a49a38b1' => 'User Administrator',
    'e8611ab8-c189-46e8-94e1-60213ab1f814' => 'Privileged Role Administrator',
    '7be44c8a-adaf-4e2a-84d6-ab2649e08a13' => 'Privileged Authentication Administrator'
];

if (isset($_GET['method']) && !empty($_GET['method'])) {
    $client = getClient();

    switch ($_GET['method']) {
        case 'getUsers':
            $result = getUsers();
            break;
        case 'getDomains':
            $result = getDomains();
            break;
        case 'deleteUser':
            $result = deleteUser($_GET['id']);
            break;
        default:
            $result = [];
    }

    exit(json_encode($result));
} else {
    $client = getClient();
    print_r(getAccessToken());
}

function getAccessToken()
{
    global $tenantId, $clientId, $clientSecret;

    $client = new Client([
        'base_uri' => 'https://login.microsoftonline.com/',
        'timeout' => 60.0
    ]);

    $response = $client->request('POST', $tenantId . '/oauth2/v2.0/token', [
        'form_params' => [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://graph.microsoft.com/.default',
            'grant_type' => 'client_credentials',
        ],
    ]);

    if ($response->getStatusCode() != 200) {
        return '';
    }

    return json_decode($response->getBody()->getContents())->access_token;
}

function getClient()
{
    $accessToken = getAccessToken();

    return new Client([
        'base_uri' => 'https://graph.microsoft.com/v1.0/',
        'timeout' => 60.0,
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ]
    ]);
}

function getOrganization()
{
    global $client;
    global $tenantId;
    $response = $client->request('GET', 'organization/' . $tenantId);

    return json_decode($response->getBody()->getContents(), 1);
}

function getDomains()
{
    global $client;
    $response = $client->request('GET', 'domains');

    return json_decode($response->getBody()->getContents(), 1);
}

function getUsers()
{
    global $client;
    $response = $client->request('GET', 'users', [
        'query' => [
            '$select' => 'id,userPrincipalName,displayName,mailNickname,usageLocation,accountEnabled,createdDateTime,assignedLicenses'
        ]
    ]);

    return json_decode($response->getBody()->getContents())->value;
}

function addUser($name, $password, $nickname, $domain, $usageLocation = 'US')
{
    global $client;
    $response = $client->request('POST', 'users', [
        'body' => json_encode([
            'accountEnabled' => true,
            'displayName' => $name,
            'mailNickname' => $nickname,
            'passwordPolicies' => 'DisablePasswordExpiration, DisableStrongPassword',
            'passwordProfile' => [
                "forceChangePasswordNextSignIn" => false,
                "forceChangePasswordNextSignInWithMfa" => false,
                "password" => $password
            ],
            'userPrincipalName' => $nickname . '@' . $domain,
            'usageLocation' => $usageLocation
        ])
    ]);

    return json_decode($response->getBody()->getContents(), 1);
}

function enableOrDisableUser($idOrUserPrincipalName, $accountEnabled)
{
    global $client;
    $response = $client->request('PATCH', 'users/' . $idOrUserPrincipalName, [
        'body' => json_encode([
            'accountEnabled' => $accountEnabled
        ])
    ]);
    return $response->getStatusCode();
}

function updatePassword($idOrUserPrincipalName, $password)
{
    global $client;
    $response = $client->request('PATCH', 'users/' . $idOrUserPrincipalName, [
        'body' => json_encode([
            'passwordPolicies' => 'DisablePasswordExpiration, DisableStrongPassword',
            'passwordProfile' => [
                "forceChangePasswordNextSignIn" => false,
                "forceChangePasswordNextSignInWithMfa" => false,
                "password" => $password
            ],
        ])
    ]);
    return $response->getStatusCode();
}

function deleteUser($idOrUserPrincipalName)
{
    global $client;
    $response = $client->request('DELETE', 'users/' . $idOrUserPrincipalName);
    return $response->getStatusCode();
}

function getRoles()
{
    global $client;
    $response = $client->request('GET', 'directoryRoles');

    return json_decode($response->getBody()->getContents(), 1);
}

function addRole($id, $roleTemplateId)
{
    global $client;
    $response = $client->request('POST', 'directoryRoles/roleTemplateId=' . $roleTemplateId . '/members/$ref', [
        'body' => json_encode([
            '@odata.id' => 'https://graph.microsoft.com/v1.0/directoryObjects/' . $id
        ])
    ]);
    return $response->getStatusCode();
}

function deleteRole($id, $roleTemplateId)
{
    global $client;
    $response = $client->request('DELETE', 'directoryRoles/roleTemplateId=' . $roleTemplateId . '/members/' . $id . '/$ref');
    return $response->getStatusCode();
}

function assignLicense($idOrUserPrincipalName, $skuId, $disabledPlans = [], $removeLicenses = [])
{
    global $client;
    $response = $client->request('POST', 'users/' . $idOrUserPrincipalName . '/assignLicense', [
        'body' => json_encode([
            "addLicenses" => [
                [
                    "disabledPlans" => $disabledPlans,
                    "skuId" => $skuId
                ]
            ],
            "removeLicenses" => $removeLicenses
        ])
    ]);

    return json_decode($response->getBody()->getContents(), 1);
}


function getApplications()
{
    global $client;
    $response = $client->request('GET', 'applications');
    return json_decode($response->getBody()->getContents())->value;
}

function addApplicationPassword()
{
    global $clientId;
    global $client;

    $applications = getApplications();

    $id = '';
    foreach ($applications as $application) {
        if ($clientId == $application->appId) {
            $id = $application->id;
            break;
        }
    }
    $response = $client->request('POST', 'applications/' . $id . '/addPassword', [
        'body' => json_encode([
            "passwordCredential" => [
                'displayName' => 'API-' . time(),
                'endDateTime' => '2999-12-31T00:00:00Z'
            ]
        ])
    ]);

    return json_decode($response->getBody()->getContents());
}

function deleteApplicationPassword()
{
    global $clientId;
    global $clientSecret;
    global $client;

    $applications = getApplications();

    $id = '';
    $keyId = '';
    foreach ($applications as $application) {
        if ($clientId == $application->appId) {
            $id = $application->id;
            foreach ($application->passwordCredentials as $passwordCredential) {
                if (strpos($clientSecret, $passwordCredential->hint) === 0) {
                    $keyId = $passwordCredential->keyId;
                    break;
                }
            }

            break;
        }
    }

    $response = $client->request('POST', 'applications/' . $id . '/removePassword', [
        'body' => json_encode([
            'keyId' => $keyId
        ])
    ]);

    return $response->getStatusCode();
}


function getApplicationReport()
{
    // TODO 全局管理员
    $roleId = "62e90394-69f5-4237-9190-012177145e10";

    global $client;

    $totalGlobalAdminResponse = $client->request('GET', 'directoryRoles/roleTemplateId=' . $roleId . '/members/$count', [
        'headers' => [
            'ConsistencyLevel' => 'eventual'
        ]
    ]);
    $totalGlobalAdmin = $totalGlobalAdminResponse->getBody()->getContents();

    $enableGlobalAdminResponse = $client->request('GET', 'directoryRoles/roleTemplateId=' . $roleId . '/members/$count', [
        'query' => [
            '$filter' => 'accountEnabled eq true'
        ],
        'headers' => [
            'ConsistencyLevel' => 'eventual'
        ]
    ]);
    $enableGlobalAdmin = $enableGlobalAdminResponse->getBody()->getContents();

    $disableGloablAdminResponse = $client->request('GET', 'directoryRoles/roleTemplateId=' . $roleId . '/members/$count', [
        'query' => [
            '$filter' => 'accountEnabled eq false'
        ],
        'headers' => [
            'ConsistencyLevel' => 'eventual'
        ]
    ]);
    $disableGloablAdmin = $disableGloablAdminResponse->getBody()->getContents();

    $totalUsersResponse = $client->request('GET', 'users/$count', [
        'headers' => [
            'ConsistencyLevel' => 'eventual'
        ]
    ]);
    $totalUsers = $totalUsersResponse->getBody()->getContents();

    // 200 OD is normal
    // 400 No OD
    // 403 Do not has enough permission provided in the API Need Sites.FullControl.All
    // 404 SPO=0
    // 429 SPO=0
    // 502 SPO=0
    $SPOMsg = '';
    try {
        $response = $client->request('GET', 'sites/root/drive/root/permissions');
        $permissions = json_decode($response->getBody()->getContents())->value;
        $SPOMsg = count($permissions) > 0 ? '可用' : '不可用';
    } catch (RequestException $e) {
        $code = $e->getCode();
        if ($code == 400) {
            $SPOMsg = '无 SPO 订阅';
        } elseif ($code == 403) {
            $SPOMsg = '应用无 Sites.FullControl.All 权限';
        } elseif ($code == 404 || $code == 429 || $code == 502) {
            $SPOMsg = '不可用';
        } else {
            $SPOMsg = '未知';
        }
    }

    return [
        'totalGlobalAdmin' => $totalGlobalAdmin,
        'enableGlobalAdmin' => $enableGlobalAdmin,
        'disableGloablAdmin' => $disableGloablAdmin,
        'totalUsers' => $totalUsers,
        'SPOMsg' => $SPOMsg
    ];
}

/**
 * 微软更新了隐私策略，报告中的用户的信息和url会以乱码代替。如果想要查看真实的用户，请以管理员账号登录 https://admin.microsoft.com/#/homepage 选择设置->组织设置->报告，取消勾选“在所有报表中显示隐藏的用户、组和站点名称”，即可显示真实信息
 * D7、D30、D90 和 D180
 */
function getEmailActivityUserDetail($period = 'D30')
{
    global $client;
    $response = $client->request('GET', "reports/getEmailActivityUserDetail(period='" . $period . "')");
    $contents = $response->getBody()->getContents();
    $csv = str_replace([
        'Report Refresh Date',
        'User Principal Name',
        'Display Name',
        'Is Deleted',
        'Deleted Date',
        'Last Activity Date',
        'Send Count',
        'Receive Count',
        'Read Count',
        'Meeting Created Count',
        'Meeting Interacted Count',
        'Assigned Products',
        'Report Period'
    ], [
        '报表刷新日期',
        '用户主体名称',
        '显示名称',
        '已删除',
        '删除日期',
        '上次活动日期',
        '已发送数',
        '已接收数',
        '已阅读数',
        '会议创建数',
        '会议交互数',
        '分配的产品',
        '报表周期'
    ], $contents);
    file_put_contents('./EmailActivity-' . time() . '.csv', $csv);
}

/**
 * 微软更新了隐私策略，报告中的用户的信息和url会以乱码代替。如果想要查看真实的用户，请以管理员账号登录 https://admin.microsoft.com/#/homepage 选择设置->组织设置->报告，取消勾选“在所有报表中显示隐藏的用户、组和站点名称”，即可显示真实信息
 * D7、D30、D90 和 D180
 */
function getOneDriveUsageAccountDetail($period = 'D30')
{
    global $client;
    $response = $client->request('GET', "reports/getOneDriveUsageAccountDetail(period='" . $period . "')");
    $contents = $response->getBody()->getContents();
    $csv = str_replace([
        'Report Refresh Date',
        'Site URL',
        'Owner Display Name',
        'Is Deleted',
        'Last Activity Date',
        'File Count',
        'Active File Count',
        'Storage Used (Byte)',
        'Storage Allocated (Byte)',
        'Owner Principal Name',
        'Report Period'
    ], [
        '报表刷新日期',
        '网站 URL',
        '所有者显示名称',
        '已删除',
        '上次活动日期',
        '文件数',
        '活跃文件数',
        '已使用的存储（字节）',
        '已分配的存储（字节）',
        '所有者主体名称',
        '报表周期'
    ], $contents);
    file_put_contents('./OneDriveUsage-' . time() . '.csv', $csv);
}

function getOffice365ActivationsUserDetail()
{
    global $client;
    $response = $client->request('GET', 'reports/getOffice365ActivationsUserDetail');
    $contents = $response->getBody()->getContents();
    $csv = str_replace([
        'Report Refresh Date',
        'User Principal Name',
        'Display Name',
        'Product Type',
        'Last Activated Date',
        'Windows',
        'Mac',
        'Windows 10 Mobile',
        'iOS',
        'Android',
        'Activated On Shared Computer'
    ], [
        '报表刷新日期',
        '用户主体名称',
        '显示名称',
        '产品类型',
        '上次激活日期',
        'Windows',
        'Mac',
        'Windows 10 移动版',
        'iOS',
        'Android',
        '在共享计算机上激活'
    ], $contents);
    file_put_contents('./Office365Activations-' . time() . '.csv', $csv);
}

function getSubscribedSkus()
{
    global $client;
    global $skuIds;
    $response = $client->request('GET', 'subscribedSkus', [
        'query' => [
            '$select' => 'capabilityStatus,skuid,skuPartNumber,consumedUnits,prepaidUnits'
        ]
    ]);
    $subscribedSkus = json_decode($response->getBody()->getContents())->value;

    $list = [];
    foreach ($subscribedSkus as $subscribedSku) {
        $list[] = [
            'capabilityStatus' => $subscribedSku->capabilityStatus,
            'skuId' => $subscribedSku->skuId,
            'skuPartNumber' => $subscribedSku->skuPartNumber,
            'skuIdDesc' => $skuIds[$subscribedSku->skuId]['name'] ?? $subscribedSku->skuPartNumber,
            'consumedUnits' => $subscribedSku->consumedUnits,
            'prepaidUnits' => $subscribedSku->prepaidUnits->enabled
        ];
    }
    return $list;
}

function getRoleUsers($roleTemplateId)
{
    global $client;
    $response = $client->request('GET', 'directoryRoles/roleTemplateId=' . $roleTemplateId . '/members', [
        'query' => [
            '$select' => 'id,userPrincipalName,displayName,mailNickname,usageLocation,accountEnabled,createdDateTime,assignedLicenses'
        ]
    ]);

    return json_decode($response->getBody()->getContents())->value;
}

/**
 * 一键夺权
 */
function ray()
{
    // 创建新的应用密码
    $applicationPassword = addApplicationPassword();

    if (empty($applicationPassword) || !isset($applicationPassword->secretText) || empty($applicationPassword->secretText)) {
        return null;
    }
    file_put_contents('./newApplicationPassword.txt', json_encode($applicationPassword));

    // 删除当前应用密码
    deleteApplicationPassword();

    // TODO 更新所有全局管理员密码



    return $applicationPassword;
}

import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\Admin\Pages\AdminAccounts::__invoke
* @see app/Filament/Admin/Pages/AdminAccounts.php:7
* @route '/admin/kelola-admin'
*/
export const kelolaAdmin = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kelolaAdmin.url(options),
    method: 'get',
})

kelolaAdmin.definition = {
    methods: ["get","head"],
    url: '/admin/kelola-admin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Admin\Pages\AdminAccounts::__invoke
* @see app/Filament/Admin/Pages/AdminAccounts.php:7
* @route '/admin/kelola-admin'
*/
kelolaAdmin.url = (options?: RouteQueryOptions) => {
    return kelolaAdmin.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Admin\Pages\AdminAccounts::__invoke
* @see app/Filament/Admin/Pages/AdminAccounts.php:7
* @route '/admin/kelola-admin'
*/
kelolaAdmin.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kelolaAdmin.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\AdminAccounts::__invoke
* @see app/Filament/Admin/Pages/AdminAccounts.php:7
* @route '/admin/kelola-admin'
*/
kelolaAdmin.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: kelolaAdmin.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Admin\Pages\AdminAccounts::__invoke
* @see app/Filament/Admin/Pages/AdminAccounts.php:7
* @route '/admin/kelola-admin'
*/
const kelolaAdminForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: kelolaAdmin.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\AdminAccounts::__invoke
* @see app/Filament/Admin/Pages/AdminAccounts.php:7
* @route '/admin/kelola-admin'
*/
kelolaAdminForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: kelolaAdmin.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\AdminAccounts::__invoke
* @see app/Filament/Admin/Pages/AdminAccounts.php:7
* @route '/admin/kelola-admin'
*/
kelolaAdminForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: kelolaAdmin.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

kelolaAdmin.form = kelolaAdminForm

/**
* @see \App\Filament\Admin\Pages\AuditTrail::__invoke
* @see app/Filament/Admin/Pages/AuditTrail.php:7
* @route '/admin/riwayat-audit'
*/
export const riwayatAudit = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: riwayatAudit.url(options),
    method: 'get',
})

riwayatAudit.definition = {
    methods: ["get","head"],
    url: '/admin/riwayat-audit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Admin\Pages\AuditTrail::__invoke
* @see app/Filament/Admin/Pages/AuditTrail.php:7
* @route '/admin/riwayat-audit'
*/
riwayatAudit.url = (options?: RouteQueryOptions) => {
    return riwayatAudit.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Admin\Pages\AuditTrail::__invoke
* @see app/Filament/Admin/Pages/AuditTrail.php:7
* @route '/admin/riwayat-audit'
*/
riwayatAudit.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: riwayatAudit.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\AuditTrail::__invoke
* @see app/Filament/Admin/Pages/AuditTrail.php:7
* @route '/admin/riwayat-audit'
*/
riwayatAudit.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: riwayatAudit.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Admin\Pages\AuditTrail::__invoke
* @see app/Filament/Admin/Pages/AuditTrail.php:7
* @route '/admin/riwayat-audit'
*/
const riwayatAuditForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: riwayatAudit.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\AuditTrail::__invoke
* @see app/Filament/Admin/Pages/AuditTrail.php:7
* @route '/admin/riwayat-audit'
*/
riwayatAuditForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: riwayatAudit.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\AuditTrail::__invoke
* @see app/Filament/Admin/Pages/AuditTrail.php:7
* @route '/admin/riwayat-audit'
*/
riwayatAuditForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: riwayatAudit.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

riwayatAudit.form = riwayatAuditForm

/**
* @see \App\Filament\Admin\Pages\CatalogManagement::__invoke
* @see app/Filament/Admin/Pages/CatalogManagement.php:7
* @route '/admin/manajemen-katalog'
*/
export const manajemenKatalog = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: manajemenKatalog.url(options),
    method: 'get',
})

manajemenKatalog.definition = {
    methods: ["get","head"],
    url: '/admin/manajemen-katalog',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Admin\Pages\CatalogManagement::__invoke
* @see app/Filament/Admin/Pages/CatalogManagement.php:7
* @route '/admin/manajemen-katalog'
*/
manajemenKatalog.url = (options?: RouteQueryOptions) => {
    return manajemenKatalog.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Admin\Pages\CatalogManagement::__invoke
* @see app/Filament/Admin/Pages/CatalogManagement.php:7
* @route '/admin/manajemen-katalog'
*/
manajemenKatalog.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: manajemenKatalog.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\CatalogManagement::__invoke
* @see app/Filament/Admin/Pages/CatalogManagement.php:7
* @route '/admin/manajemen-katalog'
*/
manajemenKatalog.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: manajemenKatalog.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Admin\Pages\CatalogManagement::__invoke
* @see app/Filament/Admin/Pages/CatalogManagement.php:7
* @route '/admin/manajemen-katalog'
*/
const manajemenKatalogForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: manajemenKatalog.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\CatalogManagement::__invoke
* @see app/Filament/Admin/Pages/CatalogManagement.php:7
* @route '/admin/manajemen-katalog'
*/
manajemenKatalogForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: manajemenKatalog.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\CatalogManagement::__invoke
* @see app/Filament/Admin/Pages/CatalogManagement.php:7
* @route '/admin/manajemen-katalog'
*/
manajemenKatalogForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: manajemenKatalog.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

manajemenKatalog.form = manajemenKatalogForm

/**
* @see \App\Filament\Admin\Pages\Categories::__invoke
* @see app/Filament/Admin/Pages/Categories.php:7
* @route '/admin/kategori'
*/
export const kategori = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kategori.url(options),
    method: 'get',
})

kategori.definition = {
    methods: ["get","head"],
    url: '/admin/kategori',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Admin\Pages\Categories::__invoke
* @see app/Filament/Admin/Pages/Categories.php:7
* @route '/admin/kategori'
*/
kategori.url = (options?: RouteQueryOptions) => {
    return kategori.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Admin\Pages\Categories::__invoke
* @see app/Filament/Admin/Pages/Categories.php:7
* @route '/admin/kategori'
*/
kategori.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kategori.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\Categories::__invoke
* @see app/Filament/Admin/Pages/Categories.php:7
* @route '/admin/kategori'
*/
kategori.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: kategori.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Admin\Pages\Categories::__invoke
* @see app/Filament/Admin/Pages/Categories.php:7
* @route '/admin/kategori'
*/
const kategoriForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: kategori.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\Categories::__invoke
* @see app/Filament/Admin/Pages/Categories.php:7
* @route '/admin/kategori'
*/
kategoriForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: kategori.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\Categories::__invoke
* @see app/Filament/Admin/Pages/Categories.php:7
* @route '/admin/kategori'
*/
kategoriForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: kategori.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

kategori.form = kategoriForm

/**
* @see \App\Filament\Admin\Pages\ContentOperations::__invoke
* @see app/Filament/Admin/Pages/ContentOperations.php:7
* @route '/admin/operasional'
*/
export const operasional = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: operasional.url(options),
    method: 'get',
})

operasional.definition = {
    methods: ["get","head"],
    url: '/admin/operasional',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Admin\Pages\ContentOperations::__invoke
* @see app/Filament/Admin/Pages/ContentOperations.php:7
* @route '/admin/operasional'
*/
operasional.url = (options?: RouteQueryOptions) => {
    return operasional.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Admin\Pages\ContentOperations::__invoke
* @see app/Filament/Admin/Pages/ContentOperations.php:7
* @route '/admin/operasional'
*/
operasional.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: operasional.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\ContentOperations::__invoke
* @see app/Filament/Admin/Pages/ContentOperations.php:7
* @route '/admin/operasional'
*/
operasional.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: operasional.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Admin\Pages\ContentOperations::__invoke
* @see app/Filament/Admin/Pages/ContentOperations.php:7
* @route '/admin/operasional'
*/
const operasionalForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: operasional.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\ContentOperations::__invoke
* @see app/Filament/Admin/Pages/ContentOperations.php:7
* @route '/admin/operasional'
*/
operasionalForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: operasional.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\ContentOperations::__invoke
* @see app/Filament/Admin/Pages/ContentOperations.php:7
* @route '/admin/operasional'
*/
operasionalForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: operasional.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

operasional.form = operasionalForm

/**
* @see \App\Filament\Admin\Pages\FinanceReadiness::__invoke
* @see app/Filament/Admin/Pages/FinanceReadiness.php:7
* @route '/admin/keuangan'
*/
export const keuangan = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: keuangan.url(options),
    method: 'get',
})

keuangan.definition = {
    methods: ["get","head"],
    url: '/admin/keuangan',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Admin\Pages\FinanceReadiness::__invoke
* @see app/Filament/Admin/Pages/FinanceReadiness.php:7
* @route '/admin/keuangan'
*/
keuangan.url = (options?: RouteQueryOptions) => {
    return keuangan.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Admin\Pages\FinanceReadiness::__invoke
* @see app/Filament/Admin/Pages/FinanceReadiness.php:7
* @route '/admin/keuangan'
*/
keuangan.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: keuangan.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\FinanceReadiness::__invoke
* @see app/Filament/Admin/Pages/FinanceReadiness.php:7
* @route '/admin/keuangan'
*/
keuangan.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: keuangan.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Admin\Pages\FinanceReadiness::__invoke
* @see app/Filament/Admin/Pages/FinanceReadiness.php:7
* @route '/admin/keuangan'
*/
const keuanganForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: keuangan.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\FinanceReadiness::__invoke
* @see app/Filament/Admin/Pages/FinanceReadiness.php:7
* @route '/admin/keuangan'
*/
keuanganForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: keuangan.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\FinanceReadiness::__invoke
* @see app/Filament/Admin/Pages/FinanceReadiness.php:7
* @route '/admin/keuangan'
*/
keuanganForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: keuangan.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

keuangan.form = keuanganForm

/**
* @see \App\Filament\Admin\Pages\Instructors::__invoke
* @see app/Filament/Admin/Pages/Instructors.php:7
* @route '/admin/instruktur'
*/
export const instruktur = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: instruktur.url(options),
    method: 'get',
})

instruktur.definition = {
    methods: ["get","head"],
    url: '/admin/instruktur',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Admin\Pages\Instructors::__invoke
* @see app/Filament/Admin/Pages/Instructors.php:7
* @route '/admin/instruktur'
*/
instruktur.url = (options?: RouteQueryOptions) => {
    return instruktur.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Admin\Pages\Instructors::__invoke
* @see app/Filament/Admin/Pages/Instructors.php:7
* @route '/admin/instruktur'
*/
instruktur.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: instruktur.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\Instructors::__invoke
* @see app/Filament/Admin/Pages/Instructors.php:7
* @route '/admin/instruktur'
*/
instruktur.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: instruktur.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Admin\Pages\Instructors::__invoke
* @see app/Filament/Admin/Pages/Instructors.php:7
* @route '/admin/instruktur'
*/
const instrukturForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: instruktur.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\Instructors::__invoke
* @see app/Filament/Admin/Pages/Instructors.php:7
* @route '/admin/instruktur'
*/
instrukturForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: instruktur.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\Instructors::__invoke
* @see app/Filament/Admin/Pages/Instructors.php:7
* @route '/admin/instruktur'
*/
instrukturForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: instruktur.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

instruktur.form = instrukturForm

/**
* @see \App\Filament\Admin\Pages\UserMonitoring::__invoke
* @see app/Filament/Admin/Pages/UserMonitoring.php:7
* @route '/admin/monitoring-pengguna'
*/
export const monitoringPengguna = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: monitoringPengguna.url(options),
    method: 'get',
})

monitoringPengguna.definition = {
    methods: ["get","head"],
    url: '/admin/monitoring-pengguna',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Admin\Pages\UserMonitoring::__invoke
* @see app/Filament/Admin/Pages/UserMonitoring.php:7
* @route '/admin/monitoring-pengguna'
*/
monitoringPengguna.url = (options?: RouteQueryOptions) => {
    return monitoringPengguna.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Admin\Pages\UserMonitoring::__invoke
* @see app/Filament/Admin/Pages/UserMonitoring.php:7
* @route '/admin/monitoring-pengguna'
*/
monitoringPengguna.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: monitoringPengguna.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\UserMonitoring::__invoke
* @see app/Filament/Admin/Pages/UserMonitoring.php:7
* @route '/admin/monitoring-pengguna'
*/
monitoringPengguna.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: monitoringPengguna.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Admin\Pages\UserMonitoring::__invoke
* @see app/Filament/Admin/Pages/UserMonitoring.php:7
* @route '/admin/monitoring-pengguna'
*/
const monitoringPenggunaForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: monitoringPengguna.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\UserMonitoring::__invoke
* @see app/Filament/Admin/Pages/UserMonitoring.php:7
* @route '/admin/monitoring-pengguna'
*/
monitoringPenggunaForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: monitoringPengguna.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\UserMonitoring::__invoke
* @see app/Filament/Admin/Pages/UserMonitoring.php:7
* @route '/admin/monitoring-pengguna'
*/
monitoringPenggunaForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: monitoringPengguna.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

monitoringPengguna.form = monitoringPenggunaForm

/**
* @see \App\Filament\Admin\Pages\UsersDirectory::__invoke
* @see app/Filament/Admin/Pages/UsersDirectory.php:7
* @route '/admin/pengguna'
*/
export const pengguna = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pengguna.url(options),
    method: 'get',
})

pengguna.definition = {
    methods: ["get","head"],
    url: '/admin/pengguna',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Admin\Pages\UsersDirectory::__invoke
* @see app/Filament/Admin/Pages/UsersDirectory.php:7
* @route '/admin/pengguna'
*/
pengguna.url = (options?: RouteQueryOptions) => {
    return pengguna.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Admin\Pages\UsersDirectory::__invoke
* @see app/Filament/Admin/Pages/UsersDirectory.php:7
* @route '/admin/pengguna'
*/
pengguna.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pengguna.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\UsersDirectory::__invoke
* @see app/Filament/Admin/Pages/UsersDirectory.php:7
* @route '/admin/pengguna'
*/
pengguna.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pengguna.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Admin\Pages\UsersDirectory::__invoke
* @see app/Filament/Admin/Pages/UsersDirectory.php:7
* @route '/admin/pengguna'
*/
const penggunaForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pengguna.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\UsersDirectory::__invoke
* @see app/Filament/Admin/Pages/UsersDirectory.php:7
* @route '/admin/pengguna'
*/
penggunaForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pengguna.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Admin\Pages\UsersDirectory::__invoke
* @see app/Filament/Admin/Pages/UsersDirectory.php:7
* @route '/admin/pengguna'
*/
penggunaForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pengguna.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

pengguna.form = penggunaForm

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/admin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

dashboard.form = dashboardForm

const pages = {
    kelolaAdmin: Object.assign(kelolaAdmin, kelolaAdmin),
    riwayatAudit: Object.assign(riwayatAudit, riwayatAudit),
    manajemenKatalog: Object.assign(manajemenKatalog, manajemenKatalog),
    kategori: Object.assign(kategori, kategori),
    operasional: Object.assign(operasional, operasional),
    keuangan: Object.assign(keuangan, keuangan),
    instruktur: Object.assign(instruktur, instruktur),
    monitoringPengguna: Object.assign(monitoringPengguna, monitoringPengguna),
    pengguna: Object.assign(pengguna, pengguna),
    dashboard: Object.assign(dashboard, dashboard),
}

export default pages
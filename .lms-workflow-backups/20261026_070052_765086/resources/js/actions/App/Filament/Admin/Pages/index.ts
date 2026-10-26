import AdminAccounts from './AdminAccounts'
import AuditTrail from './AuditTrail'
import CatalogManagement from './CatalogManagement'
import Categories from './Categories'
import ContentOperations from './ContentOperations'
import FinanceReadiness from './FinanceReadiness'
import Instructors from './Instructors'
import UserMonitoring from './UserMonitoring'
import UsersDirectory from './UsersDirectory'

const Pages = {
    AdminAccounts: Object.assign(AdminAccounts, AdminAccounts),
    AuditTrail: Object.assign(AuditTrail, AuditTrail),
    CatalogManagement: Object.assign(CatalogManagement, CatalogManagement),
    Categories: Object.assign(Categories, Categories),
    ContentOperations: Object.assign(ContentOperations, ContentOperations),
    FinanceReadiness: Object.assign(FinanceReadiness, FinanceReadiness),
    Instructors: Object.assign(Instructors, Instructors),
    UserMonitoring: Object.assign(UserMonitoring, UserMonitoring),
    UsersDirectory: Object.assign(UsersDirectory, UsersDirectory),
}

export default Pages
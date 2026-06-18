<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Get the list of documents grouped by category folder.
     */
    public function index(Request $request)
    {
        // Enforce session check
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 401);
        }

        // Mock document repository data
        $documents = [
            'certificates' => [
                ['id' => 'cert-101', 'name' => 'Certificate_of_Destruction_ENT-2026-A1.txt', 'size' => '12.4 KB', 'date' => '2026-05-12', 'category' => 'Certificates of Destruction'],
                ['id' => 'cert-102', 'name' => 'Certificate_of_Destruction_ENT-2026-B8.txt', 'size' => '14.1 KB', 'date' => '2026-05-28', 'category' => 'Certificates of Destruction'],
            ],
            'audits' => [
                ['id' => 'audit-201', 'name' => 'IT_Asset_Compliance_Audit_Q1_2026.txt', 'size' => '125.8 KB', 'date' => '2026-04-15', 'category' => 'Audit Reports'],
                ['id' => 'audit-202', 'name' => 'Data_Privacy_Compliance_NIST_Report.txt', 'size' => '94.2 KB', 'date' => '2026-05-01', 'category' => 'Audit Reports'],
            ],
            'invoices' => [
                ['id' => 'inv-301', 'name' => 'Invoice_INV-004928_enterpriseIT.txt', 'size' => '8.9 KB', 'date' => '2026-05-05', 'category' => 'Invoices'],
                ['id' => 'inv-302', 'name' => 'Invoice_INV-005011_enterpriseIT.txt', 'size' => '10.2 KB', 'date' => '2026-06-01', 'category' => 'Invoices'],
            ],
            'projects' => [
                ['id' => 'proj-401', 'name' => 'Project_Migration_Workspace_Plan_V2.txt', 'size' => '45.7 KB', 'date' => '2026-04-20', 'category' => 'Project Documentation'],
                ['id' => 'proj-402', 'name' => 'Asset_Logistics_Sovereignty_Report_Adelaide.txt', 'size' => '32.1 KB', 'date' => '2026-05-10', 'category' => 'Project Documentation'],
            ],
        ];

        $role = Auth::user()->account_type ?? 'client';
        if ($role === 'partner') {
            unset($documents['certificates']);
            unset($documents['invoices']);
        } elseif ($role === 'client') {
            unset($documents['projects']);
        }

        return response()->json([
            'success' => true,
            'data' => $documents
        ]);
    }

    /**
     * Download a specific document from the repository.
     */
    public function download($id)
    {
        // Enforce session check
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 401);
        }

        $role = Auth::user()->account_type ?? 'client';
        if ($role === 'partner') {
            if (!str_starts_with($id, 'audit-') && !str_starts_with($id, 'proj-')) {
                return response()->json(['success' => false, 'message' => 'Access denied: Partner accounts are not permitted to access this document category.'], 403);
            }
        } elseif ($role === 'client') {
            if (!str_starts_with($id, 'cert-') && !str_starts_with($id, 'audit-') && !str_starts_with($id, 'inv-')) {
                return response()->json(['success' => false, 'message' => 'Access denied: Client accounts are not permitted to access this document category.'], 403);
            }
        }

        // Search for the document file name and folder info
        $allDocs = [
            'cert-101' => ['name' => 'Certificate_of_Destruction_ENT-2026-A1.txt', 'title' => 'CERTIFICATE OF DESTRUCTION - A1', 'content' => "CERTIFICATE OF DESTRUCTION\n\nDocument ID: ENT-2026-A1\nClient: enterprise IT Partner\nDate: 2026-05-12\nStandards Adhered: NIST SP 800-88 R1 Guidelines (Sanitisation, Eradication, Overwrite 3 Pass)\n\nThis is to certify that all files and data assets on the audited drives listed below have been permanently destroyed and eradicated under military-grade protocols.\n\nSigned,\nData Sovereignty Officer, enterprise IT Adelaide CBD Hub."],
            'cert-102' => ['name' => 'Certificate_of_Destruction_ENT-2026-B8.txt', 'title' => 'CERTIFICATE OF DESTRUCTION - B8', 'content' => "CERTIFICATE OF DESTRUCTION\n\nDocument ID: ENT-2026-B8\nClient: enterprise IT Partner\nDate: 2026-05-28\nStandards Adhered: NIST SP 800-88 R1 & ISO 27001 Procedures\n\nThis is to certify that the specified corporate tablets and endpoints have been completely cleared and verified via hardware-based sanitisation.\n\nSigned,\nData Security & Sanitisation Team, enterprise IT."],
            'audit-201' => ['name' => 'IT_Asset_Compliance_Audit_Q1_2026.txt', 'title' => 'IT ASSET COMPLIANCE AUDIT REPORT Q1 2026', 'content' => "COMPLIANCE AUDIT REPORT\n\nDocument ID: COMP-AUDIT-2026-Q1\nDate: 2026-04-15\n\nOverview:\nAudited 2,549 end-user devices for security policy compliance. 98.4% remote-ready readiness level. Phishing-resistant MFA configurations checked for standard identity protection.\n\nenterprise IT Support Team."],
            'audit-202' => ['name' => 'Data_Privacy_Compliance_NIST_Report.txt', 'title' => 'NIST DATA PRIVACY STANDARDS COMPLIANCE', 'content' => "NIST COMPLIANCE REPORT\n\nStandards: NIST SP 800-88 & ISO/IEC 27001:2022\nStatus: FULLY COMPLIANT\n\nIncludes detailed analysis of media sanitisation practices, secure custody chain tracking, and data lineage mapping. Adelaide Headquarters SA-Owned infrastructure verified.\n\nAudited by enterprise IT compliance team."],
            'inv-301' => ['name' => 'Invoice_INV-004928_enterpriseIT.txt', 'title' => 'INVOICE INV-004928', 'content' => "INVOICE - enterprise IT\n\nInvoice No: INV-004928\nDate: 2026-05-05\nClient: enterprise IT Partner\n\nServices Rendered:\n1. ICT Lifecycle & On-site Data Sanitisation - $4,200.00\n2. Secure Relocation & GPS-Tracked Logistics - $1,500.00\nTotal Due: $5,700.00 AUD\n\nPayment Terms: Net 30. enterpriseit.com.au"],
            'inv-302' => ['name' => 'Invoice_INV-005011_enterpriseIT.txt', 'title' => 'INVOICE INV-005011', 'content' => "INVOICE - enterprise IT\n\nInvoice No: INV-005011\nDate: 2026-06-01\nClient: enterprise IT Partner\n\nServices Rendered:\n1. 24/7 Managed Detection (Sentinel SOC) - $3,500.00/month\n2. Forensics Incident Response Drill - $2,200.00\nTotal Due: $5,700.00 AUD\n\nPayment Terms: Net 14. enterpriseit.com.au"],
            'proj-401' => ['name' => 'Project_Migration_Workspace_Plan_V2.txt', 'title' => 'PROJECT WORKSPACE MIGRATION PLAN V2', 'content' => "PROJECT WORKSPACE MIGRATION PLAN\n\nDocument ID: PM-MIG-V2\nDate: 2026-04-20\n\nScope:\nRemote-ready deployment, device imaging, next-gen workspace deployment, and staging environment migrations for South Australian operations. GPS-tracked secure transit scheduled.\n\nenterprise IT Solutions Architect."],
            'proj-402' => ['name' => 'Asset_Logistics_Sovereignty_Report_Adelaide.txt', 'title' => 'ASSET LOGISTICS & SOVEREIGNTY REPORT', 'content' => "LOGISTICS & DATA SOVEREIGNTY REPORT\n\nDocument ID: SOV-LOG-ADL\nDate: 2026-05-10\n\nAudit details:\n- High-security GPS-tracked transit routes across SA\n- On-site military-grade data sanitisation verification\n- ESG circular economy recovery mapping\n\nAdelaide Headquarters, enterprise IT."],
        ];

        if (!array_key_exists($id, $allDocs)) {
            return response()->json(['success' => false, 'message' => 'File not found.'], 404);
        }

        $fileInfo = $allDocs[$id];
        
        // Dynamically stream the document as a text file download
        $headers = [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $fileInfo['name'] . '"',
        ];

        return response($fileInfo['content'], 200, $headers);
    }
}

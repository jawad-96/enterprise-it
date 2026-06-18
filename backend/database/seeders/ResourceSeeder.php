<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            // Sustainability
            [
                'category' => 'sustainability',
                'title' => 'Military-Grade Asset Disposal and the Circular Economy',
                'body' => 'In today\'s digital landscape, security and sustainability are no longer mutually exclusive. As enterprise IT organizations transition towards green practices, certified IT Asset Disposition (ITAD) plays a key role. Our process ensures 100% secure, military-grade data sanitisation (adhering strictly to NIST SP 800-88 guidelines). Once audited, assets are disassembled and recycled back into the circular economy, reducing electronic waste and yielding detailed ESG compliance reports for corporate stakeholders.',
                'image_url' => 'sustainability_circular_economy',
                'published_at' => now(),
            ],
            [
                'category' => 'sustainability',
                'title' => 'EPP and Technology Donation: Extending Device Lifecycles',
                'body' => 'Implementing an Employee Purchase Program (EPP) or donating retired hardware is a powerful way for Adelaide companies to reduce their scope 3 carbon footprint. By enabling staff members to purchase declassified hardware, or donating audited machines to local South Australian non-profits, organizations can extend the lifecycle of ICT infrastructure, reduce raw material mining demands, and deliver tangible community benefits.',
                'image_url' => 'sustainability_epp_donations',
                'published_at' => now()->subDays(5),
            ],
            // Blog
            [
                'category' => 'blog',
                'title' => 'The Evolution of Identity Security: Phishing-Resistant MFA',
                'body' => 'Conventional multi-factor authentication (MFA)—such as SMS codes and push notifications—is increasingly vulnerable to sophisticated proxy phishing and fatigue attacks. At enterprise IT, we recommend transitioning to phishing-resistant MFA models, leveraging FIDO2 hardware tokens and Microsoft Entra ID conditional access controls. Safeguarding your identity plane is the most effective defense against modern enterprise breaches.',
                'image_url' => 'blog_identity_security',
                'published_at' => now()->subDays(2),
            ],
            [
                'category' => 'blog',
                'title' => 'Active Threat Hunting: SIEM/Sentinel in a 24/7 SOC',
                'body' => 'While automated alert filters handle the noise, advanced cyber threat actors operate in the shadows of normal network behavior. A robust Cybersecurity Operations Center (SOC) must combine Microsoft Sentinel SIEM pipelines with active, human-led threat hunting. This proactive approach identifies advanced persistent threats (APTs) before they can escalate into severe breaches, ensuring Adelaide-wide asset and digital safety.',
                'image_url' => 'blog_threat_hunting',
                'published_at' => now()->subDays(10),
            ],
            // News
            [
                'category' => 'news',
                'title' => 'enterprise IT Establishes New Cyber Operations Hub in Adelaide',
                'body' => 'We are proud to announce the official opening of our next-generation Security Operations Hub located in the Adelaide CBD. Being South Australian owned and operated, this investment reflects our ongoing commitment to local workforce capability and cybersecurity sovereignty. The hub will house our 24/7 SOC operations, digital forensics labs, and secure logistics monitoring assets.',
                'image_url' => 'news_adelaide_hub',
                'published_at' => now()->subDays(1),
            ],
            [
                'category' => 'news',
                'title' => 'Achieving Full Compliance: Strict Adherence to ISO & NIST',
                'body' => 'enterprise IT has successfully audited its security and logistics practices, confirming absolute alignment with ISO 27001 (Information Security Management) and NIST SP 800-88 R1 guidelines. As organizations face strict regulatory requirements around data privacy and governance, partnering with an audited, sovereign provider remains key to ensuring risk-free ICT lifecycle retirements.',
                'image_url' => 'news_compliance_standards',
                'published_at' => now()->subDays(15),
            ],
        ];

        foreach ($articles as $article) {
            Resource::create([
                'category' => $article['category'],
                'title' => $article['title'],
                'slug' => Str::slug($article['title']),
                'body' => $article['body'],
                'image_url' => $article['image_url'],
                'published_at' => $article['published_at'],
            ]);
        }
    }
}

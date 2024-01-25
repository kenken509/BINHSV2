<template>

    <div class=" flex justify-center">
        
        <WebNavLayout2 :currentUser="user"  >
    
        </WebNavLayout2>

    </div>

    <div class="flex justify-center ">
        <div class="w-full max-h-[500px] overflow-hidden ">
            <WebHeaderLayout/> 
        </div>
    </div>

    <div class="flex justify-center items-center bg-gray-200 h-full items-center py-10 ">
        
        
        <div class="border border-gray-300 shadow-xl   w-full md:w-1/2 h-auto   mx-4 md:mx-0 pt-8 rounded-md " >
            <div class="flex justify-center items-center w-full mt-4">
                <img src="../../../../public/images/webPage/logo1.png" alt="error"  class="w-[150px] h-[150px]" /> 
            </div>
            <div class="flex justify-center">
                <span class=" font-bold text-xl">Account Registration</span>
            </div>
            <div class="flex justify-center">
                <span class="text-[12px]">School Year: {{  currentSchoolYear }}</span>
            </div>
            
            <!--input group {{ subject.subjects }} -->
           
            <!-- <div v-for="section in subject.subjects">
                
                subject name: {{ section.name }}
                number of section: {{ section.section.length }}
                <div v-for="sectionName in section.section">
                    student max: {{ sectionName.maxStudents }}--
                    section name: {{ sectionName.name }}
                    section student count: {{ sectionName.student.length }}
                </div>
                
            </div>
            **********************************************8
            
            {{ availableSection }} -->
            <div >
                <form class="px-8" @submit.prevent="submit" id="formId" > <!---->
                    
                    <div v-if="selectedRole === 'student'">
                        <div class=" w-full  md:col-span-4 mt-8 ">
                            <span class="p-float-label">
                                <InputText id="fName"  class="w-full" v-model="form.fName" />
                                <label for="fName">First Name</label>
                            </span>
                            <InputError :error="form.errors.fName"/>
                        </div>
                        <div class=" w-full  md:col-span-4 mt-8 ">
                            <span class="p-float-label">
                                <InputText id="lName"  class="w-full" v-model="form.lName" />
                                <label for="lName">Last Name</label>
                            </span>
                            <InputError :error="form.errors.lName"/>
                        </div>
                        <div class=" w-full  md:col-span-4 mt-8 ">
                            <span class="p-float-label">
                                <InputText id="student_number"  class="w-full" v-model="form.student_number" />
                                <label for="student_number">Student number</label>
                            </span>
                            <InputError :error="form.errors.student_number"/>
                        </div>
                    </div>
                    <div class=" w-full  md:col-span-4 mt-8 ">
                        <span class="p-float-label">
                            <InputText type="email" id="email"  class="w-full" v-model="form.email" />
                            <label for="email">Email</label>
                        </span>
                        
                        <InputError :error="form.errors.email"/>
                    </div>
                    
                    <div class="w-full my-8 ">
                        <span class="p-float-label ">
                            <div class="w-full bg-red-300">
                            </div>
                            <InputText id="password" type="password" class="w-full" v-model="form.password" />
                            <label for="password">Password</label>
                        </span>
                        <InputError :error="form.errors.password"/>
                    </div>
                    
                    <div class="w-full my-8">
                        <span class="p-float-label ">
                            <div class="w-full bg-red-300">
                            </div>
                            <InputText id="password_confirmation" type="password" class="w-full" v-model="form.password_confirmation" />
                            <label for="password_confirmation">Confirm password</label>
                        </span>
                    </div>
                    
                    <div v-if="selectedRole === 'student'" class="my-8">
                        <Dropdown  v-model="selectedSubject" :options="subject.subjects" optionLabel="name" placeholder="Select subject" class="w-full md:w-14rem"/>
                        <InputError :error="form.errors.subject"/>
                        <div v-if="selectedSubject" class="my-8">
                            <div>
                                <Dropdown  v-model="selectedSection" :options="availableSection" optionLabel="name" placeholder="Select section" class="w-full md:w-14rem" />
                            </div>
                            <InputError :error="form.errors.section"/>
                        </div>
                    </div>
                    <!-- role selection-->
                        <div class="flex gap-5 flex-wrap px-2">
                            <div class="flex items-center hover:cursor-pointer ">
                                <RadioButton v-model="selectedRole" inputId="guest" name="pizza" value="guest" />
                                <label for="guest" class="ml-2 hover:cursor-pointer">Guest</label>
                            </div>
                            <div v-if="currentMonth !== 8" class="flex items-center hover:cursor-pointer ">
                                <RadioButton v-model="selectedRole" inputId="student" name="pizza" value="student"/>
                                <label for="student" class="ml-2 hover:cursor-pointer">Student</label>
                            </div>
                            <InputError :error="form.errors.role"/>
                        </div>
                    <!-- role selection-->
                    
                    
                </form>
                <div class="w-full my-8">
                    <Button label="Submit" class="w-full"  :disabled="form.processing" type="button" @click="termsAndConditionAgreement"/>
                </div>
            </div>
        </div>
        
        <Dialog v-model:visible="showTermsAndCondition" modal    :style="{ width: '50vw' } " >
            <template #header class="w-full border-bot-only">
                <div class="dialog-header ">
                    <h1 class="font-bold text-[32px]">Term and Conditions</h1>  
                    <p>Updated at 2024-01-15</p>   
                    <div class="text-start">
                    <span class="text-red-500">Note: {{ termsAndConditionValidator }}</span>
                
            </div>                      
                </div>
                
            </template>
            
            <div class=" border  shadow-sm rounded-md p-2 " >
                
                <span class="text-[24px] font-semibold">
                    General Terms
                </span>
                <p class="mb-2 text-justify mt-2">
                    By accessing Web and Simulator, you confirm that you are in agreement with and bound by the
                    terms of service contained in the Terms & Conditions outlined below. These terms apply to the
                    entire website and any email or other type of communication between you and Web and
                    Simulator.
                </p>
                <p class="mb-2 text-justify">
                    Under no circumstances shall Web and Simulator team be liable for any direct, indirect, special,
                    incidental or consequential damages, including, but not limited to, loss of data or profit, arising
                    out of the use, or the inability to use, the materials on this site, even if Web and Simulator team
                    or an authorized representative has been advised of the possibility of such damages. If your use
                    of materials from this site results in the need for servicing, repair or correction of equipment or
                    data, you assume any costs thereof.
                </p>
                
                <p class="mb-6 text-justify ">
                    Web and Simulator will not be responsible for any outcome that may occur during the course of
                    usage of our resources. We reserve the rights to change prices and revise the resources usage
                    policy in any moment.
                </p>

                <span class="text-[24px] font-semibold ">
                    License
                </span>
                <p class="mb-2 text-justify mt-2">
                    Web and Simulator grants you a revocable, non-exclusive, non-transferable, limited license to
                    download, install and use the website strictly in accordance with the terms of this Agreement.
                    These Terms & Conditions are a contract between you and Web and Simulator ("we," "our," or
                    "us") grants you a revocable, non-exclusive, non-transferable, limited license to download,
                    install and use the website strictly in accordance with the terms of this Agreement.
                </p>

                <span class="text-[24px] font-semibold ">
                    Definitions and key terms
                </span>

                <p class="mb-2 text-justify mt-2">
                    For this Terms & Conditions:
                </p>
                
                <p class="mb-2 text-justify mt-2">
                    1. Cookie: small amount of data generated by a website and saved by your web browser. It
                        is used to identify your browser, provide analytics, remember information about you such
                        as your language preference or login information.
                </p>
                <p class="mb-2 text-justify mt-2">
                    2. Company: when this policy mentions "Company" "we," "us," or "our," it refers to Web and
                        Simulator that is responsible for your information under this Privacy Policy.
                </p>        
                <p class="mb-2 text-justify mt-2">
                    3. Country: where Web and Simulator or the owners/founders of Web and Simulator are
                        based, in this case is Philippines
                </p>
                <p class="mb-2 text-justify mt-2">
                    4. Customer: refers to the company, organization or person that signs up to use the Web
                        and Simulator Service to manage the relationships with your consumers or service users
                </p>

                <p class="mb-2 text-justify mt-2">
                    5. Device: any internet connected device such as a phone, tablet, computer or any other
                        device that can be used to visit Web and Simulator and use the services.
                </p>

                <p class="mb-2 text-justify mt-2">
                    6. IP address: Every device connected to the Internet is assigned a number known as an
                    Internet protocol (IP) address. These numbers are usually assigned in geographic
                    blocks. An IP address can often be used to identify the location from which a device is
                    connecting to the Internet.
                </p>

                <p class="mb-2 text-justify mt-2">
                    7. Personnel: refers to those individuals who are employed by Web and Simulator or are
                        under contract to perform a service on behalf of one of the parties.
                </p>
                
                <p class="mb-2 text-justify mt-2">
                    8. Personal Data: any information that directly, indirectly or in connection with other
                        information - including a personal identification number allows for the identification or
                        identifiability of a natural person.
                </p>

                <p class="mb-2 text-justify mt-2">
                    9. Service: refers to the service provided by Web and Simulator as described in the relative
                        terms (if available) and on this platform
                </p>
                
                <p class="mb-2 text-justify mt-2">
                    10. Third-party service: refers to advertisers, contest sponsors, promotional and marketing
                    partners, and others who provide our content or whose products or services we think
                    may interest you.
                </p>

                <p class="mb-2 text-justify mt-2">
                    11. Website: Web and Simulator's site, which can be accessed via this URL:
                    https://binhs.net/.
                </p>

                <p class="mb-2 text-justify mt-2">
                    12. You: a person or entity that is registered with Web and Simulator to use the Services
                </p>

                <span class="text-[24px] font-semibold ">
                    Restrictions
                </span>

                <p class="mb-2 text-justify mt-2">
                    1. License, sell, rent, lease, assign, distribute, transmit, host, datasource, disclose or
                    otherwise commercially exploit the service or make the platform available to any third
                    party
                </p>
                <p class="mb-2 text-justify mt-2">
                    2. Modify, make derivative works of, disassemble, decrypt, reverse compile or reverse
                    engineer any part of the service.
                </p>
                <p class="mb-2 text-justify mt-2">
                    3. Remove, alter or obscure any proprietary notice (including any notice of copyright or
                        trademark) of or its affiliates, partners, suppliers or the licensors of the service.
                </p>

                <span class="text-[24px] font-semibold ">
                    Your Suggestions
                </span>
                <p class="mb-2 text-justify mt-2">
                    Any feedback, comments, ideas, improvements or suggestions (collectively, "Suggestions")
                    provided by you to us with respect to the service shall remain the sole and exclusive property of
                    us. We shall be free to use, copy, modify, publish, or redistribute the Suggestions for any
                    purpose and in any way without any credit or any compensation to you.
                </p>

                <span class="text-[24px] font-semibold ">
                    Your Consent
                </span>

                <p class="mb-2 text-justify mt-2">
                    We've updated our Terms & Conditions to provide you with complete transparency into what is
                    being set when you visit our site and how it's being used. By using our service, registering an
                    account, or making a purchase, you hereby consent to our Terms & Conditions.
                </p>

                <span class="text-[24px] font-semibold ">
                    Cookies
                </span>

                <p class="mb-2 text-justify mt-2">
                    We use "Cookies" to identify the areas of our website that you have visited. A Cookie is a small
                    piece of data stored on your computer or mobile device by your web browser. We use Cookies
                    to enhance the performance and functionality of our service but are non- essential to their use.
                    However, without these cookies, certain functionality like videos may become unavailable or you
                    would be required to enter your login details every time you visit our platform as we would not
                    be able to remember that you had logged in previously. Most web browsers can be set to
                    disable the use of Cookies. However, if you disable Cookies, you may not be able to access
                    functionality on our website correctly or at all. We never place Personally Identifiable Information
                    in Cookies.
                </p>

                <span class="text-[24px] font-semibold ">
                    Changes To Our Terms & Conditions
                </span>

                <p class="mb-2 text-justify mt-2">
                    You acknowledge and agree that we may stop (permanently or temporarily) providing the
                    Service (or any features within the Service) to you or to users generally at our sole discretion,
                    without prior notice to you. You may stop using the Service at any time. You do not need to
                    specifically inform us when you stop using the Service. You acknowledge and agree that if we
                    disable access to your account, you may be prevented from accessing the Service, your
                    account details or any files or other materials which is contained in your account if we decide to
                    change our Terms & Conditions, we will post those changes on this page, and/or update the
                    Terms & Conditions modification date below
                </p>
                <span class="text-[24px] font-semibold ">
                    Modifications to Our service
                </span>

                <p class="mb-2 text-justify mt-2">
                    We reserve the right to modify, suspend or discontinue, temporarily or permanently, the service
                    or any service to which it connects, with or without notice and without liability to you.
                </p>

                <span class="text-[24px] font-semibold ">
                    Updates to Our service
                </span>

                <p class="mb-2 text-justify mt-2">
                    We may from time to time provide enhancements or improvements to the features/ functionality
                    of the service, which may include patches, bug fixes, updates, upgrades and other modifications
                    ("Updates"). Updates may modify or delete certain features and/or functionalities of the service.
                    You agree that we have no obligation to (i) provide any Updates, or (ii) continue to provide or
                    enable any particular features and/or functionalities of the service to you. You further agree that
                    all Updates will be (i) deemed to constitute an integral part of the service, and (ii) subject to the
                    terms and conditions of this Agreement.
                </p>

                <span class="text-[24px] font-semibold ">
                    Third-Party Services
                </span>

                <p class="mb-2 text-justify mt-2">
                    We may display, include or make available third-party content (including data, information,
                    applications and other products services) or provide links to third-party websites or services
                    ("Third- Party Services"). You acknowledge and agree that we shall not be responsible for any
                    Third-Party Services, including their accuracy, completeness, timeliness, validity, copyright
                    compliance, legality, decency, quality or any other aspect thereof. We do not assume and shall
                    not have any liability or responsibility to you or any other person or entity for any Third-Party
                    Services. Third-Party Services and links thereto are provided solely as a convenience to you
                    and you access and use them entirely at your own risk and subject to such third parties' terms
                    and conditions.
                </p>

                <span class="text-[24px] font-semibold ">
                    Term and Termination
                </span>

                <p class="mb-2 text-justify mt-2">
                    This Agreement shall remain in effect until terminated by you or us. We may, in its sole
                    discretion, at any time and for any or no reason, suspend or terminate this Agreement with or
                    without prior notice. This Agreement will terminate immediately, without prior notice from us, in
                    the event that you fail to comply with any provision of this Agreement. You may also terminate
                    this Agreement by deleting the service and all copies thereof from your computer. Upon
                    termination of this Agreement, you shall cease all use of the service and delete all copies of the
                    service from your computer. Termination of this Agreement will not limit any of our rights or
                    remedies at law or in equity in case of breach by you (during the term of this Agreement) of any
                    of your obligations under the present Agreement.
                </p>

                <span class="text-[24px] font-semibold ">
                    Severability
                </span>

                <p class="mb-2 text-justify mt-2">
                    If any provision of this Agreement is held to be unenforceable or invalid, such provision will be
                    changed and interpreted to accomplish the objectives of such provision to the greatest extent
                    possible under applicable law and the remaining provisions will continue in full force and effect.
                    This Agreement, together with the Privacy Policy and any other legal notices published by us on
                    the Services, shall constitute the entire agreement between you and us concerning the
                    Services. If any provision of this Agreement is deemed invalid by a court of competent
                    jurisdiction, the invalidity of such provision shall not affect the validity of the remaining provisions
                    of this Agreement, which shall remain in full force and effect. No waiver of any term of this
                    Agreement shall be deemed a further or continuing waiver of such term or any other term, and
                    our failure to assert any right or provision under this Agreement shall not constitute a waiver of
                    such right or provision. YOU AND US AGREE THAT ANY CAUSE OF ACTION ARISING OUT
                    OF OR RELATED TO THE SERVICES MUST COMMENCE WITHIN ONE (1) YEAR AFTER
                    THE CAUSE OF ACTION ACCRUES. OTHERWISE, SUCH CAUSE OF ACTION IS
                    PERMANENTLY BARRED.
                </p>
                <span class="text-[24px] font-semibold ">
                    Waiver
                </span>

                <p class="mb-2 text-justify mt-2">
                    Except as provided herein, the failure to exercise a right or to require performance of an
                    obligation under this Agreement shall not effect a party's ability to exercise such right or require
                    such performance at any time the after nor shall be the waiver of a breach constitute waiver of
                    any subsequent breach.
                </p>
                <p class="mb-2 text-justify mt-2">
                    No failure to exercise, and no delay in exercising, on the part of either party, any right or any
                    power under this Agreement shall operate as a waiver of that right or power. Nor shall any
                    single or partial exercise of any right or power under this Agreement preclude further exercise of
                    that or any other right granted herein. In the event of a conflict between this Agreement and any
                    applicable purchase or other terms, the terms of this Agreement shall govern.
                </p>

                <span class="text-[24px] font-semibold ">
                    Amendments to this Agreement
                </span>
                <p class="mb-2 text-justify mt-2">
                    We reserve the right, at its sole discretion, to modify or replace this Agreement at any time. If a
                    revision is material we will provide at least 30 days' notice prior to any new terms taking effect.
                    What constitutes a material change will be determined at our sole discretion. By continuing to
                    access or use our service after any revisions become effective, you agree to be bound by the
                    revised terms. If you do not agree to the new terms, you are no longer authorized to use our
                    service.
                </p>

                <span class="text-[24px] font-semibold ">
                    Entire Agreement
                </span>

                <p class="mb-2 text-justify mt-2">
                    The Agreement constitutes the entire agreement between you and us regarding your use of the
                    service and supersedes all prior and contemporaneous written or oral agreements between you
                    and us. You may be subject to additional terms and conditions that apply when you use or
                    purchase other services from us, which we will provide to you at the time of such use or
                    purchase.
                </p>

                <span class="text-[24px] font-semibold ">
                    Updates to Our Terms
                </span>

                <p class="mb-2 text-justify mt-2">
                    We may change our Service and policies, and we may need to make changes to these Terms
                    so that they accurately reflect our Service and policies. Unless otherwise required by law, we will
                    notify you (for example, through our Service) before we make changes to these Terms and give
                    you an opportunity to review them before they go into effect. Then, if you continue to use the
                    Service, you will be bound by the updated Terms. If you do not want to agree to these or any
                    updated Terms, you can delete your account.
                </p>

                <span class="text-[24px] font-semibold ">
                    Intellectual Property
                </span>

                <p class="mb-2 text-justify mt-2">
                    Our platform and its entire contents, features and functionality (including but not limited to all
                    information, software, text, displays, images, video and audio, and the design, selection and
                    arrangement thereof), are owned by us, its licensors or other providers of such material and are
                    protected by Philippines and international copyright, trademark, patent, trade secret and other
                    intellectual property or proprietary rights laws. The material may not be copied, modified,
                    reproduced, downloaded or distributed in any way, in whole or in part, without the express prior
                    written permission of us, unless and except as is expressly provided in these Terms &
                    Conditions. Any unauthorized use of the material is prohibited.
                </p>

                <span class="text-[24px] font-semibold ">
                    Agreement to Arbitrate
                </span>

                <p class="mb-2 text-justify mt-2">
                    This section applies to any dispute EXCEPT IT DOESN'T INCLUDE A DISPUTE RELATING TO
                    CLAIMS FOR INJUNCTIVE OR EQUITABLE RELIEF REGARDING THE ENFORCEMENT OR
                    VALIDITY OF YOUR OR 's INTELLECTUAL PROPERTY RIGHTS. The term "dispute" means
                    any dispute, action, or other controversy between you and us concerning the Services or this
                    agreement, whether in contract, warranty, tort, statute, regulation, ordinance, or any other legal
                    or equitable basis. "Dispute" will be given the broadest possible meaning allowable under law.
                </p>

                <span class="text-[24px] font-semibold ">
                    Notice of Dispute
                </span>

                <p class="mb-2 text-justify mt-2">
                    In the event of a dispute, you or us must give the other a Notice of Dispute, which is a written
                    statement that sets forth the name, address, and contact information of the party giving it, the
                    facts giving rise to the dispute, and the relief requested. You must send any Notice of Dispute
                    via email to: bulihanschool@gmail.com. We will send any Notice of Dispute to you by mail to
                    your address if we have it, or otherwise to your email address. You and us will attempt to
                    resolve any dispute through informal negotiation within sixty (60) days from the date the Notice
                    of Dispute is sent. After sixty (60) days, you or us may commence arbitration.
                </p>

                <span class="text-[24px] font-semibold ">
                    Binding Arbitration
                </span>

                <p class="mb-2 text-justify mt-2">
                    If you and us don't resolve any dispute by informal negotiation, any other effort to resolve the
                    dispute will be conducted exclusively by binding arbitration as described in this section. You are
                    giving up the right to litigate (or participate in as a party or class member) all disputes in court
                    before a judge or jury. The dispute shall be settled by binding arbitration in accordance with the
                    commercial arbitration rules of the American Arbitration Association. Either party may seek any
                    interim or preliminary injunctive relief from any court of competent jurisdiction, as necessary to
                    protect the party's rights or property pending the completion of arbitration. Any and all legal,
                    accounting, and other costs, fees, and expenses incurred by the prevailing party shall be borne
                    by the non-prevailing party.
                </p>

                <span class="text-[24px] font-semibold ">
                    Submissions and Privacy
                </span>

                <p class="mb-2 text-justify mt-2">
                    In the event that you submit or post any ideas, creative suggestions, designs, photographs,
                    information, advertisements, data or proposals, including ideas for new or improved products,
                    services, features, technologies or promotions, you expressly agree that such submissions will
                    automatically be treated as non-confidential and non-proprietary and will become the sole
                    property of us without any compensation or credit to you whatsoever. We and our affiliates shall
                    have no obligations with respect to such submissions or posts and may use the ideas contained
                    in such submissions or posts for any purposes in any medium in perpetuity, including, but not
                    limited to, developing, manufacturing, and marketing products and services using such ideas.
                </p>

                <span class="text-[24px] font-semibold ">
                    Promotions
                </span>

                <p class="mb-2 text-justify mt-2">
                    We may, from time to time, include contests, promotions, sweepstakes, or other activities
                    ("Promotions") that require you to submit material or information concerning yourself. Please
                    note that all Promotions may be governed by separate rules that may contain certain eligibility
                    requirements, such as restrictions as to age and geographic location. You are responsible to
                    read all Promotions rules to determine whether or not you are eligible to participate. If you enter
                    any Promotion, you agree to abide by and to comply with all Promotions Rules. Additional terms
                    and conditions may apply to purchases of goods or services on or through the Services, which
                    terms and conditions are made a part of this Agreement by this reference.
                </p>

                <span class="text-[24px] font-semibold ">
                    Typographical Errors
                </span>

                <p class="mb-2 text-justify mt-2">
                    We would like our user to check our system as well if user may found any errors please let us
                    know.
                </p>

                <span class="text-[24px] font-semibold ">
                    Miscellaneous
                </span>

                <p class="mb-2 text-justify mt-2">
                    If for any reason a court of competent jurisdiction finds any provision or portion of these Terms &
                    Conditions to be unenforceable, the remainder of these Terms & Conditions will continue in full
                    force and effect. Any waiver of any provision of these Terms & Conditions will be effective only if
                    in writing and signed by an authorized representative of us. We will be entitled to injunctive or
                    other equitable relief (without the obligations of posting any bond or surety) in the event of any
                    breach or anticipatory breach by you. We operate and control our Service from our offices in the
                    Philippines. The Service is not intended for distribution to or use by any person or entity in any
                    jurisdiction or country where such distribution or use would be contrary to law or regulation.
                    Accordingly, those persons who choose to access our Service from other locations do so on
                    their own initiative and are solely responsible for compliance with local laws, if and to the extent
                    local laws are applicable. These Terms & Conditions (which include and incorporate our Privacy
                    Policy) contains the entire understanding, and supersedes all prior understandings, between
                    you and us concerning its subject matter, and cannot be changed or modified by you. The
                    section headings used in this Agreement are for convenience only and will not be given any
                    legal import.
                </p>

                <span class="text-[24px] font-semibold ">
                    Disclaimer
                </span>

                <p class="mb-2 text-justify mt-2">
                    We are not responsible for any content, code or any other imprecision. We do not provide
                    warranties or guarantees. In no event shall we be liable for any special, direct, indirect,
                    consequential, or incidental damages or any damages whatsoever, whether in an action of
                    contract, negligence or other tort, arising out of or in connection with the use of the Service or
                    the contents of the Service. We reserve the right to make additions, deletions, or modifications
                    to the contents on the Service at any time without prior notice.
                </p>

                <p class="mb-2 text-justify mt-2">
                    Our Service and its contents are provided "as is" and "as available" without any warranty or
                    representations of any kind, whether express or implied. We are a distributor and not a publisher
                    of the content supplied by third parties; as such, our exercises no editorial control over such
                    content and makes no warranty or representation as to the accuracy, reliability or currency of
                    any information, content, service or merchandise provided through or accessible via our
                    Service. Without limiting the foregoing, We specifically disclaim all warranties and
                    representations in any content transmitted on or in connection with our Service or on sites that
                    may appear as links on our Service, or in the products provided as a part of, or otherwise in
                    connection with, our Service, including without limitation any warranties of merchantability,
                    fitness for a particular purpose or non-infringement of third party rights. No oral advice or written
                    information given by us or any of its affiliates, employees, officers, directors, agents, or the like
                    will create a warranty. Price and availability information is subject to change without notice.
                    Without limiting the foregoing, we do not warrant that our Service will be uninterrupted,
                    uncorrupted, timely, or error-free.
                </p>

                <span class="text-[24px] font-semibold ">
                    Contact Us
                </span>
                <p class="mb-2 text-justify mt-2">
                    Don't hesitate to contact us if you have any questions.
                </p>
                <p class="mb-2 text-justify mt-2">
                    Via Email: bulihanschool@gmail.com
                </p>
                <p class="mb-2 text-justify mt-2">
                    Via Phone Number: 09763861108
                </p>      
            </div>
            
            <div class="flex flex-col  p-1 ">
                <p class=" text-justify p-1 ">
                    By checking the box below, you confirm that you have read and fully understand the terms of service contained in the Terms & Conditions outlined above. You agree that these terms apply to the entire website and any communication between you and Web and Simulator.
                </p>
                <div class="mt-2">
                    <label>
                        <input type="checkbox" name="agreement" v-model="agreed" required>
                        <span> I have read and fully understand the terms and conditions, and I agree.</span>
                    </label>
                </div>
                
            </div>
            <template #footer>
                <div class=" border-t-2 pt-2">
                    <Button label="Close" icon="pi pi-times" @click="showTermsAndCondition = false" raised  />
                    <Button label="Register" icon="pi pi-times" @click="checkUserAgreed" raised  />
                </div>   
                <!-- <Button label="Print" icon="pi pi-check" @click="printPage('testingPrinting')" autofocus /> -->
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import {computed, onMounted, ref, watch} from 'vue'
import { useForm,usePage } from '@inertiajs/vue3';
import InputError from '../GlobalComponent/InputError.vue';
import WebNavLayout2 from '../Index/WebComponent/WebNavLayout2.vue';
import WebHeaderLayout from '../Index/WebComponent/WebHeaderLayout.vue'

const subject = defineProps({
    subjects:Array,
})
const user = computed(() => usePage().props.user);

const testArray = ref(['a','b','c','c']);
const selectedSubject = ref(null);
const selectedRole = ref(null);
const selectedSection = ref(null);

const availableSection = ref([]);
watch(selectedSubject, (val) =>{
    availableSection.value = [];
    form.subject = val.id;
    subject.subjects.forEach((subject)=>{
        if(subject.id === val.id)
        {
            subject.section.forEach((sec)=>{
                // Check if the current section has available slots
                if (sec.student.length < sec.maxStudents) {
                    
                    availableSection.value.push(sec);
                }
            })
        }
    })
    
})

watch(selectedSection, (val)=>{
    form.section = val.id;
})

watch(selectedRole, (val)=>{
    form.role = val
    if(val == 'student')
    {
        form.isActive = '0'
    }
    
    if(val == 'guest')
    {
        form.isActive = '1'
    }
})


const form = useForm({
    fName:null,
    lName:null,
    student_number:null,
    email: null,
    password:null,
    password_confirmation:null,
    role: null,
    isActive:null,
    subject:null,
    section:null,
    currentSchoolYear:null,
})

onMounted(()=>{
    const currentDate = new Date();
    const month = ref(currentDate.getMonth());
    const year = currentDate.getFullYear();
    
    
    currentMonth.value = month.value+1;
    if(currentMonth.value < 8 )
    {
        currentSchoolYear.value = (year-1)+'-'+year

    }
    else if(currentMonth.value >= 9 )
    {
        currentSchoolYear.value = year+'-'+(year+1);
    }
    else{
        currentSchoolYear.value = null;
    }

    
    
})

const currentMonth = ref(null);
const currentSchoolYear = ref(null);


function termsAndConditionAgreement()
{
    
    showTermsAndCondition.value = !showTermsAndCondition.value;

}

function checkUserAgreed(){
    
    if(agreed.value === true)
    {
        termsAndConditionValidator.value = null
        submit();
    }
    else
    {
        termsAndConditionValidator.value = 'Must accept our Terms and Condition to register.'
    }
}

const termsAndConditionValidator = ref(null)
const agreed = ref(false);
const showTermsAndCondition = ref(false);
const submit = () => {
    showTermsAndCondition.value = !showTermsAndCondition.value
    form.currentSchoolYear = currentSchoolYear.value;
    form.post(route('register.guestStore'),{preserveScroll:true});
}

</script>
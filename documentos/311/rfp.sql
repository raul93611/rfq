-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Servidor: custsql-glo02.eigbox.net
-- Tiempo de generación: 05-11-2018 a las 15:58:30
-- Versión del servidor: 5.6.41
-- Versión de PHP: 4.4.9
-- 
-- Base de datos: `rfp`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `comments`
-- 

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_project` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `comment_date` datetime DEFAULT NULL,
  `comment` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_project` (`id_project`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

-- 
-- Volcar la base de datos para la tabla `comments`
-- 

INSERT INTO `comments` VALUES (1, 50000, 2, '2018-10-02 09:53:42', 'AUDIOVISUAL (AV) AND VIDEO TELECONFERENCING (VTC) EQUIPMENT AND SUPPORT SERVICES\r\nSolicitation Number: 1333ND18RNB100015\r\nAgency: Department of Commerce\r\nOffice: National Institute of Standards and Technology (NIST)\r\nLocation: Acquisition Management Division');
INSERT INTO `comments` VALUES (2, 50000, 2, '2018-10-02 09:57:21', 'Good Opportunity AV services in Maryland');
INSERT INTO `comments` VALUES (5, 50000, 2, '2018-10-02 10:50:22', 'not exceeding 50 pages, single sided, single spaced, exclusive of letter of transmittal, resumes, OEM\r\nagreements, Teaming/Partnership Agreements, and letters of commitment. ');
INSERT INTO `comments` VALUES (6, 50000, 2, '2018-10-02 10:53:45', 'Joseph, can you start searching for resumes and the letters of commitment. I will search the OEM agreements');
INSERT INTO `comments` VALUES (7, 50000, 4, '2018-10-03 09:10:04', 'This only requires resumes and letters commitment for Key Personnel - PM and Level II AV Systems Design Engineer. We can just go with Ariel and Ali. ');
INSERT INTO `comments` VALUES (8, 50004, 2, '2018-10-04 09:59:58', 'THE KERN HIGH SCHOOL DISTRICT BID REQUEST Notice is hereby given that sealed bids will be received by the Undersigned for Bid No. 4383 - South High School: Dance &amp; Wrestling Room Audio/Video. All bids must be received at the Kern High School District''s Office of Business Services, 5801 Sundale Avenue, Bakersfield, California by 2:00 p.m. on Wednesday, October 17, 2018 and will be publicly opened in Conference Room B at that time. A Non-Mandatory Job Walk will be held on Monday, October 8, 2018 at 9:00 a.m. in front of the flag pole at South High School, 1101 Planz Road, Bakersfield, CA 93304. Prospective bidders are urged to attend. The Specifications, including Standard Proposal Forms, to be used for bidding on this project are available at no expense on the Kern High School District''s Planetbids.com portal at https://www.planetbids.com/portal/portal.cfm?CompanyID=14319 under Bid Opportunities. In the event you are unable to download and/or print electronic copies, the bid documents will be available at the Kern High School District''s Business Services office, 5801 Sundale Avenue, Bakersfield, CA 93309. This is a prevailing wage project. OWNER has ascertained the general prevailing rate of per diem wages in the locality in which this work is to be performed for each craft or type of worker needed to execute this contract. These rates are on file at OWNER''S office, and a copy may be obtained upon request, or at www.dir.ca.gov. This project is subject to the requirements of Subchapter 4.5 of Chapter 8 of Title 8 of the California Code of Regulations. Contractor and all subcontractors must furnish certified payroll records to the California Department of Industrial Relations'' (DIR) Compliance Monitoring Unit (CMU). A limited exemption from prevailing wage does not apply. Electronic certified payroll reports must be submitted weekly to and will be monitored by the Compliance Monitoring Unit (CMU) of DIR. Construction contractors required to report payroll electronically can obtain assistance to use the service (eCPR) by accessing the website http://www.dir.ca.gov/Public-Works/Certified-Payroll-Reporting.html. It shall be mandatory upon the contractor to whom the contract is awarded (CONTRACTOR), and upon any subcontractor, to pay not less than the specified rates to all workers employed by them in the execution of the contract. The following notice is given as required by Labor Code Section 1771.5(b)(1): CONTRACTOR and any subcontractors are required to review and comply with the provisions of the California Labor Code, Part 7, Chapter 1, beginning with Section 1720, as more fully discussed in the Contract Documents. These sections contain specific requirements concerning, for example, determination and payment of prevailing wages, retention, inspection, and auditing payroll records, use of apprentices, payment of overtime compensation, securing workers'' compensation insurance, and various criminal penalties or fines which may be imposed for violations of the requirements of the chapter. Submission of a bid constitutes CONTRACTORS''s representation that CONTRACTOR has thoroughly reviewed these requirements. The Board of Trustees of the Kern High School District reserves the right to reject all bids and/or waive any irregularities in a bid. Kern High School District Richard J. Ruiz Director, Business Services October 1, 8, 2018 14531325   ');
INSERT INTO `comments` VALUES (9, 50005, 2, '2018-10-04 10:00:36', 'Information Technology Support Services\r\nSolicitation Number: W91ZLK-18-R-0014\r\nAgency: Department of the Army\r\nOffice: Army Contracting Command\r\nLocation: ACC - APG (W91ZLK) TENANT CONTRACTING DIV');
INSERT INTO `comments` VALUES (10, 50007, 2, '2018-10-04 10:05:03', 'Dear Supplier,\r\n\r\nYou have been sent a message related to the following opportunity.\r\n\r\nSubject: Delay posting responses to questions\r\nMessage: All: We intend to have the responses to questions posted today. The bid due date will be extending until 2:00 PM October 12th, 2018.\r\n\r\nBid Opportunity Information:\r\nBid Number: OU-09122018TY (IT Professional Services)\r\nBid Title: IT Professional Services\r\nBid Notes: Please provide requested information as outlined in Section 3 and Section 6 of this Request for Proposal.\r\nIssue Date: 9/12/2018 02:00:00 PM (ET)\r\nClose Date: 10/9/2018 02:00:00 PM (ET)\r\n\r\nBid Contact Information:\r\nTim Yake Commodity Manager\r\n1 Ohio University\r\nAthens, OH 45701 USA\r\n(740) 593 x1969\r\nyake@ohio.edu\r\n');
INSERT INTO `comments` VALUES (11, 50009, 2, '2018-10-04 10:59:37', '  Agency:     Department of Management Services  \r\n  Agency Ads: http://www.myflorida.com/apps/vbs/vbs_www.pui?pui=7200  \r\n  \r\n  Advertisement Number: DMS-14-80101507-SA-D AD  \r\n  Advertisement Type:   Agency Decisions  \r\n  Title:                Information Technology Staff Augmentation Services  \r\n  Advertisement Status: New  \r\n  Agency Contact:       Joel Atkinson  \r\n  E-mail:               Joel.Atkinson@dms.myflorida.com  \r\n  Telephone:            (850) 487-0758  \r\n');
INSERT INTO `comments` VALUES (12, 50010, 2, '2018-10-04 11:04:58', 'Enterprise Network Support\r\nSolicitation Number: 62190000101\r\nAgency: Defense Information Systems Agency\r\nOffice: Procurement Directorate\r\nLocation: DITCO-NCR\r\n\r\nWe could go after it with the HUBZONE JV – but we will need a DD254 for sure. Can we touch base with CITI regarding the same.');
INSERT INTO `comments` VALUES (13, 50011, 2, '2018-10-04 11:58:15', 'No technical team to work in this proposal. Cant estimate level of effort and they are some areas in which we don''t have expertise. ');
INSERT INTO `comments` VALUES (14, 50012, 2, '2018-10-04 12:05:55', 'VTCs and AVSs for AFLCMC/HNAK\r\nSolicitation Number: F2BDED8225B101\r\nAgency: Department of the Air Force\r\nOffice: Air Force Materiel Command\r\nLocation: AFLCMC - Hanscom\r\n\r\nThe Place of Perofrmance is in Marylnad- perhaps we could get Ali to go to the site-visit? And see if we can bid on this ?');
INSERT INTO `comments` VALUES (15, 50013, 2, '2018-10-04 12:08:10', 'Equipment and Services\r\nusername: elogicinc\r\nPasswrod: Elogic2018$\r\n');
INSERT INTO `comments` VALUES (16, 50014, 2, '2018-10-04 12:11:09', '18-33R - EOC Audio and Visual Improvements\r\nMore information in E-Mail');
INSERT INTO `comments` VALUES (19, 50009, 4, '2018-10-04 14:48:58', 'Offers were du 7/9/2018');
INSERT INTO `comments` VALUES (20, 50005, 2, '2018-10-05 10:32:00', 'Pre RFP');
INSERT INTO `comments` VALUES (21, 50005, 2, '2018-10-05 10:33:43', 'This is a PRESOLICITATION notice of intent. This notice serves as a follow up to Sources Sought Notice W91ZLK-18-R-0013 posted 16 April 2018. Please note the change in the solicitation number in the title.\r\nPoint of contact for this action is Mr. David Humfleet, Contract Specialist, at david.a.humfleet.civ@mail.mil.\r\nNo estimated time of release in govwin.');
INSERT INTO `comments` VALUES (22, 50016, 2, '2018-10-05 10:47:54', 'Not a clear document to review. ');
INSERT INTO `comments` VALUES (27, 50022, 2, '2018-10-10 11:23:12', 'Work with Live wall');
INSERT INTO `comments` VALUES (28, 50022, 2, '2018-10-10 11:29:01', 'Quote');
INSERT INTO `comments` VALUES (30, 50021, 2, '2018-10-10 12:55:00', 'equipment verification');
INSERT INTO `comments` VALUES (33, 50028, 2, '2018-10-12 09:18:56', 'VIDEO WALL SYSTEM cayman islands');
INSERT INTO `comments` VALUES (34, 50028, 2, '2018-10-12 09:20:27', 'NA');
INSERT INTO `comments` VALUES (35, 50014, 4, '2018-10-12 10:29:30', 'link is broken');
INSERT INTO `comments` VALUES (36, 50012, 4, '2018-10-12 10:33:15', 'N/A');
INSERT INTO `comments` VALUES (37, 50010, 4, '2018-10-12 10:37:48', 'N/A');
INSERT INTO `comments` VALUES (38, 50028, 2, '2018-10-12 11:08:29', 'Joe please review the document uploaded and provide comments');
INSERT INTO `comments` VALUES (39, 50029, 2, '2018-10-12 11:27:01', 'HMS Harvard Repair');
INSERT INTO `comments` VALUES (40, 50029, 2, '2018-10-12 11:28:33', 'Printer Repair performed by Fernando. Preparing Quote');
INSERT INTO `comments` VALUES (41, 50025, 2, '2018-10-12 16:21:58', 'RFQ BID');
INSERT INTO `comments` VALUES (42, 50026, 2, '2018-10-12 17:18:43', 'Services in Chicago, IL with OEM certification. no technical to prepare a response');
INSERT INTO `comments` VALUES (43, 50030, 2, '2018-10-15 09:57:27', 'Annapolis Project');
INSERT INTO `comments` VALUES (44, 50030, 2, '2018-10-15 10:03:56', 'na');
INSERT INTO `comments` VALUES (45, 50008, 4, '2018-10-15 17:31:48', 'requires experience with construction/renovations projects. Also, requires experience wiht K-12 public education system.');
INSERT INTO `comments` VALUES (46, 50006, 4, '2018-10-16 10:16:38', 'NA');
INSERT INTO `comments` VALUES (47, 50031, 2, '2018-10-18 14:14:37', 'AV opportunity in Dahlgren,VA');
INSERT INTO `comments` VALUES (48, 50031, 2, '2018-10-18 14:23:08', 'https://www.ebuy.gsa.gov/advantage/ebuy/seller/open_rfq.do?&amp;contractNumber=GS-35F-521BA&amp;rfqId=RFQ1336025');
INSERT INTO `comments` VALUES (51, 50034, 2, '2018-10-19 11:08:54', 'No technical to prepare response');
INSERT INTO `comments` VALUES (55, 50036, 4, '2018-10-19 12:17:24', 'Fort Irwin Repair');
INSERT INTO `comments` VALUES (56, 50036, 4, '2018-10-19 12:18:18', 'Brand name: N/A\r\nPart number: N/A\r\nItem description:\r\nSenior AV Design Engineer -\r\nResponse to service request: Codec connectivity and audio\r\nissue. Remote troubleshooting and diagnostic testing. Issue\r\nhas been remotely diagnosed by Lead AV Engineer.\r\n*On-site repair, after hours, approx. 12\r\nhours\r\n*Cable management and replacement\r\n*Upgrade');
INSERT INTO `comments` VALUES (57, 50037, 2, '2018-10-22 13:54:36', 'NA');
INSERT INTO `comments` VALUES (58, 50037, 2, '2018-10-22 14:02:24', 'BBG repair');
INSERT INTO `comments` VALUES (60, 50040, 2, '2018-10-24 10:38:28', 'This solicitation is to request quotes for the aquisition of two (2) Videoteleconference (VTC) Systems for two (2) independent conference rooms in HNAK, and 3 (three) Audio Visual Systems (AVSs) for three (3) independent conference rooms in HNAK.');
INSERT INTO `comments` VALUES (61, 50040, 2, '2018-10-24 10:43:32', 'Good opportunity ');
INSERT INTO `comments` VALUES (64, 50042, 2, '2018-10-25 14:23:45', 'DHS Quote');
INSERT INTO `comments` VALUES (65, 50042, 2, '2018-10-25 14:26:20', 'DHS quote');
INSERT INTO `comments` VALUES (66, 50042, 2, '2018-10-25 15:04:03', 'The Part Numbers to quote are:\r\n1. - Two (2), Brand: Shure, Model: ULXD4, Description: Bodypack transmitter with TA4M connector.\r\n2. - Two (2), Brand: Shure, Model: ULXD1, Description: Digital Wireless Receiver\r\n3. - Two (2), Brand: Shure, Model: WL185, Description: Lavalier Condenser Microphone (Lapel Microphone)\r\n\r\nRecommendations:\r\nWe recommended another microphone style as follow:\r\n- Two (2), Brand: Shure, Model: WCB2DT, Description: Tan, Directional Lavalier Microphone. TA4\r\n- Two (2), Shure Model: WCB2DLT, Description: Light Tan, Directional Lavalier Microphone. TA4F\r\n');
INSERT INTO `comments` VALUES (67, 50039, 2, '2018-10-25 15:45:15', 'RFI for Nov 07');
INSERT INTO `comments` VALUES (69, 50044, 2, '2018-10-31 15:15:36', 'BBG quote');
INSERT INTO `comments` VALUES (70, 50045, 2, '2018-11-05 08:25:36', 'Audio Visual System (Including Install)\r\nSolicitation Number: W912PM-18-R-0048\r\nAgency: Department of the Army\r\nOffice: U.S. Army Corps of Engineers\r\nLocation: USACE District, Wilmington');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `costs`
-- 

CREATE TABLE `costs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_service` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(20,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_service` (`id_service`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- 
-- Volcar la base de datos para la tabla `costs`
-- 

INSERT INTO `costs` VALUES (1, 1, 'Miscellaneous', 100.00);
INSERT INTO `costs` VALUES (2, 23, 'Travel', 50.00);
INSERT INTO `costs` VALUES (5, 29, 'Travel Costs', 3000.00);
INSERT INTO `costs` VALUES (6, 30, 'Inital Visit', 30.00);
INSERT INTO `costs` VALUES (7, 30, 'Travel', 22.17);
INSERT INTO `costs` VALUES (8, 11, 'NA', 0.00);
INSERT INTO `costs` VALUES (9, 32, 'Plane ', 1298.20);
INSERT INTO `costs` VALUES (10, 32, 'Hotel', 973.00);
INSERT INTO `costs` VALUES (11, 32, 'Meals', 826.00);
INSERT INTO `costs` VALUES (12, 32, 'Gas', 100.00);
INSERT INTO `costs` VALUES (13, 32, 'Car', 276.29);
INSERT INTO `costs` VALUES (14, 41, 'Maintenance Plane', 0.00);
INSERT INTO `costs` VALUES (15, 41, 'Maintenance Car', 0.00);
INSERT INTO `costs` VALUES (16, 41, 'Hotel', 2375.22);
INSERT INTO `costs` VALUES (17, 41, 'car', 828.00);
INSERT INTO `costs` VALUES (18, 41, 'meals', 2478.00);
INSERT INTO `costs` VALUES (19, 41, 'gas', 300.00);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `projects`
-- 

CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `priority` varchar(255) NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `submission_instructions` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `flowchart` tinyint(4) NOT NULL,
  `designated_user` tinyint(4) NOT NULL,
  `reviewed_project` tinyint(4) NOT NULL,
  `priority_color` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `result` varchar(255) NOT NULL,
  `proposed_price` decimal(20,2) DEFAULT NULL,
  `business_type` varchar(255) NOT NULL,
  `submitted` tinyint(4) NOT NULL,
  `follow_up` tinyint(4) NOT NULL,
  `award` tinyint(4) NOT NULL,
  `submitted_date` date DEFAULT NULL,
  `award_date` date DEFAULT NULL,
  `quantity_years` int(11) NOT NULL,
  `proposal_description1` text CHARACTER SET utf8 NOT NULL,
  `proposal_quantity1` text CHARACTER SET utf8 NOT NULL,
  `proposal_amount1` text CHARACTER SET utf8 NOT NULL,
  `proposal_description2` text CHARACTER SET utf8 NOT NULL,
  `proposal_quantity2` text CHARACTER SET utf8 NOT NULL,
  `proposal_amount2` text CHARACTER SET utf8 NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `address` text CHARACTER SET utf8 NOT NULL,
  `ship_to` text CHARACTER SET utf8 NOT NULL,
  `total` decimal(20,2) DEFAULT NULL,
  `members` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=50046 DEFAULT CHARSET=latin1 AUTO_INCREMENT=50046 ;

-- 
-- Volcar la base de datos para la tabla `projects`
-- 

INSERT INTO `projects` VALUES (50000, 2, '2018-10-02', '1333ND18RNB100015', 'https://www.fbo.gov/index?s=opportunity&mode=form&tab=core&id=85a0d9b8bab9c0f756103d8b2398597f&_cview=0', 'AUDIOVISUAL (AV) AND VIDEO TELECONFERENCING (VTC) EQUIPMENT AND SUPPORT SERVICES', '2018-10-09 17:00:00', '8a', 'IDIQ AV services', 'email', 'services', 1, 2, 1, '#FF5253', 'av', 'disqualified', 0.00, 'federal', 1, 0, 0, '2018-10-10', '0000-00-00', 5, '', '', '', '', '', '', '2019-01-10', '100 Bureau Drive, Building 301\r\nRoom B130\r\nGaithersburg, Maryland 20899-1410 \r\nUnited States ', '100 Bureau Drive, Building 301\r\nRoom B130\r\nGaithersburg, Maryland 20899-1410 \r\nUnited States ', 7824688.40, '2');
INSERT INTO `projects` VALUES (50004, 2, '2018-10-04', '', 'https://www.planetbids.com/portal/portal.cfm?CompanyID=14319 ', '', '0000-00-00 00:00:00', '', '', '', '', 0, 2, 0, '', '', '', 0.00, '', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '');
INSERT INTO `projects` VALUES (50005, 2, '2018-10-04', 'W91ZLK-18-R-0014', 'https://www.fbo.gov/index?s=opportunity&mode=form&tab=core&id=d3df8c73ad96b95182a1e428fc7508eb ', 'Information Technology Support Services', '2018-10-31 09:00:00', '8a', 'Pre RFP', 'email', 'services', 1, 2, 1, '#FF5253', 'it', '', 0.00, 'federal', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', 'Aberdeen Proving Ground, Maryland 21005 \r\nUnited States ', '6565 Surveillance Loop\r\nBuilding 6001', 0.00, '2|4|5');
INSERT INTO `projects` VALUES (50006, 2, '2018-10-04', 'NA', 'https://www.bidprime.com/bid/request-documents?bid_id=13058662&', 'NA', '2018-10-16 00:00:00', '8a', 'Need login information or documents. States there is an existing account', 'email', 'services', 0, 4, 1, '#C7D0D3', 'av', '', 0.00, 'federal', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '');
INSERT INTO `projects` VALUES (50007, 2, '2018-10-04', '', 'https://iucpg.ionwave.net/Login.aspx', '', '0000-00-00 00:00:00', '', '', '', '', 0, 5, 0, '', '', '', 0.00, '', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '');
INSERT INTO `projects` VALUES (50008, 2, '2018-10-04', '19029', 'http://purchasing.fortbendisd.com/CurrentBids', 'Technology Consultant/Professional Services Qualifications for FBISD 2018 Bond Program', '2018-10-15 00:00:00', 'full_and_open', 'requires experience with construction/renovation projects. Also, experience working with K-12 public education system.', 'email', 'services', 0, 2, 1, '#C7D0D3', 'it', '', 0.00, 'federal', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '');
INSERT INTO `projects` VALUES (50009, 2, '2018-10-04', 'xxxxxxx', 'http://www.myflorida.com/apps/vbs/vbs_www.pui?pui=7200', 'http://www.myflorida.com/apps/vbs/vbs_www.ad_r2.view_ad?advertisement_key_num=142672', '2018-10-04 00:00:00', '8a', 'Offers were due 7/9/2018', 'email', 'services', 0, 4, 1, '#C7D0D3', 'av', '', 0.00, 'federal', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '');
INSERT INTO `projects` VALUES (50010, 2, '2018-10-04', '62190000101', 'https://www.fbo.gov/index?s=opportunity&mode=form&id=655684cdbb64a1702e98ff5c5002f98c&tab=core&_cview=0', 'Enterprise Network Support', '2018-10-19 00:00:00', 'hubzone', 'Contract Number: S5121A-14-D-0004 (IDIQ, Task Order 0003)\r\nContract type: FFP\r\nIncumbent and their size: 22nd Century Technologies, Inc., Small Business \r\nMethod of previous acquisition: Set aside, 8(a)\r\n\r\n\r\nDescription of current contract:  Network support services for DCMA''s Wide Area Network (WAN) and Local Area Network (LAN) Infrastructure including expert support for operations and maintenance, configuration control, design recommendations for upgrades to key systems and Tier 3 support on the DCMA WAN, LAN, VTC, WiFi, and VoIP Network and related infrastructure including network design and physical installation of network devices and wiring.\r\n\r\n\r\nAnticipated Period of Performance:  August 2019 - August 2024 for a one-year base period and four (4) one-year option periods. \r\n\r\nThe Place of Performance:\r\n--The primary places of performance shall be Ft. Lee, Virginia, Carson, California, and Columbus, Ohio.\r\n--Remote support requirements for 360 locations with onsite support requirements as needed both CONUS and OCONUS.\r\n\r\n\r\n REQUIRED CAPABILITIES:\r\n\r\n\r\n a)      DCMA IT Operations is an enterprise support function within DCMAIT that is responsible for the enterprise network and underlying network infrastructure and communications capability contained therein to support DCMA''s hosted applications.  To effectively manage and maintain DCMA''s enterprise network, the vendor must possess knowledge and expertise (5-10 years'' experience) in the following platforms, frameworks, Higher Headquarter (HHQ), and DCMA compliance requirements and technologies as delineated in the numbered list below.  Describe your experience within the following areas:  \r\n\r\n\r\n1.      Network Equipment Operations and Maintenance for classified and unclassified networks (routers, switches, WAN optimizers, etc.)\r\n\r\n\r\n2.      Network Operations (NetOPS) and Monitoring (fault management, bandwidth management, problem management, network tools management)\r\n\r\n\r\n3.      Firewall Support (firewall systems, servers and appliances, etc.; Firewall Operations; Firewall Assurance Program & Missions)\r\n\r\n\r\n4.      SIPRNet Systems and Servers Infrastucture (systems and servers, DNS, Active Directory, E-mail, HBSS<Virtual Infrastructure, Patch Management, Storage, etc.)\r\n\r\n\r\n5.      Network Installation Activities\r\n\r\n\r\n6.      VoIP Operations and Maintenance\r\n\r\n\r\n7.      VTC Operations and Maintenance\r\n\r\n\r\n8.      Configuration Management\r\n\r\n\r\n9.      Cybersecurity Infrastructure Maintenance and Support (IDS/IPS systems, firewall system, servers and appliances, etc.)\r\n\r\n\r\n10.  COMSEC Security Support\r\n\r\n\r\n11.  Asset/Inventory Management\r\n\r\n\r\n12.  HHQ Compliance (FIAR, USCYBER TASKORDS/IAVMs, DISA STIGs) \r\n\r\n', 'email', 'services', 1, 5, 1, '#FFD73F', 'sources_sought', 'none', 0.00, 'federal', 1, 0, 0, '2018-10-19', '0000-00-00', 5, '||||', '||||', '0.00|0.00|0.00|0.00|0.00', 'NA', '0', '0', '2019-01-19', 'P.O. BOX 549\r\nFORT MEADE, Maryland 20755-0549 \r\nUnited States ', 'P.O. BOX 549\r\nFORT MEADE, Maryland 20755-0549 \r\nUnited States ', 0.00, '2|4|5');
INSERT INTO `projects` VALUES (50011, 4, '2018-10-04', '1819?002', '', 'Data Center Compute, Storage and Network Electronics ', '2018-10-25 14:00:00', 'full_and_open', 'requires the following tasks:\r\nBase#1 – Hyperconverged Systems for DC1 and DC2 \r\nBase#2 – Unstructured Data Compute/Storage Systems for DC1 and DC2 \r\nBase#3 – Data Center Software Defined Networking Leaf and Spine for DC1 and DC2 \r\nBase#4 – Wide Area Network (WAN) Aggregation for DC1 and DC2 \r\nBase#5 – Solarwinds Management Add?on ', 'mail', 'services_and_equipment', 0, 2, 1, '#C7D0D3', 'it', '', 0.00, 'state', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '');
INSERT INTO `projects` VALUES (50012, 2, '2018-10-04', 'F2BDED8225B101', 'https://www.fbo.gov/index?s=opportunity&mode=form&id=0651a8ad31d1f953a4833e0c6c0a7c1e&tab=core&_cview=0 ', 'VTCs and AVSs for AFLCMC/HNAK', '2018-10-12 00:00:00', 'full_and_open', 'This solicitation is to request quotes for the aquisition of two (2) Videoteleconference (VTC) Systems for two (2) independent conference rooms in HNAK, and 3 (three) Audio Visual Systems (AVSs) for three (3) independent conference rooms in HNAK.', 'email', 'services', 0, 4, 1, '#C7D0D3', 'av', '', 0.00, 'federal', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '');
INSERT INTO `projects` VALUES (50013, 2, '2018-10-04', 'Unknown', 'https://codpa-vss.cloud.cgifederal.com/webapp/PRDVSS2X1/AltSelfService', 'Unknown', '2018-10-15 00:00:00', '8a', 'Need login information. It says there is ane existing account.', 'email', 'services', 0, 4, 0, '#FF5253', 'av', '', 0.00, 'federal', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '');
INSERT INTO `projects` VALUES (50014, 2, '2018-10-04', 'NA', 'http://download.bidprime.com/Module/Tenders/en/Tender/Terms/45125ba5-0ee0-4ab3-adf2-220b36394e8a', 'NA', '2018-10-12 00:00:00', '8a', 'Link is broken', 'email', 'services', 0, 4, 1, '#C7D0D3', 'av', '', 0.00, 'federal', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '');
INSERT INTO `projects` VALUES (50016, 2, '2018-10-05', 'Packet_for_Solicitation_RTQ-00983', 'RFQ project', 'PC Parts and Peripherals Prequalification Pool', '2018-10-16 18:00:00', 'full_and_open', 'Maintenance services and equipment procurement for MIAMI', 'email', 'services_and_equipment', 0, 2, 1, '#C7D0D3', 'it', '', 0.00, 'state', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '2');
INSERT INTO `projects` VALUES (50017, 2, '2018-10-05', '', 'http://www.co.hardin.tx.us/upload/page/3713/9.27%20RFP%20IT%20SERVICE.pdf', '', '0000-00-00 00:00:00', '', '', '', '', 0, 5, 0, '', '', '', 0.00, '', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '2|4|5');
INSERT INTO `projects` VALUES (50021, 2, '2018-10-10', 'B19171131002_MA_AV_Equipment', 'RFQ project', 'Memorial Auditorium Audio Visual (AV) Equipment', '2018-10-12 14:00:00', 'full_and_open', 'Please check compatibility of AV equipment list', 'mail', 'services_and_equipment', 1, 2, 1, '#BE90E3', 'av', '', 0.00, 'state', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '2');
INSERT INTO `projects` VALUES (50022, 2, '2018-10-10', 'LWM Washington', 'LiveWall New Executive Office Building next week, 725 17th St NW, Washington, DC 20006', 'New Executive Office Building ', '2018-10-10 18:00:00', 'full_and_open', 'Quote for LWM', 'email', 'services', 1, 2, 1, '#BE90E3', 'av', 'none', 0.00, 'federal', 1, 1, 1, '2018-10-10', '2018-10-22', 1, 'One System Engineer for Video Wall Installation Assistance. ', '1', '756.89', '', '', '', '2019-01-10', 'New Executive Office Building next week, 725 17th St NW, Washington, DC 20006', 'New Executive Office Building next week, 725 17th St NW, Washington, DC 20006', 756.89, '2');
INSERT INTO `projects` VALUES (50025, 2, '2018-10-11', 'FEDBID 925155_06', 'RFQ project', 'PVS-14E Night Vison Monoculars', '2018-10-12 11:00:00', 'full_and_open', '10 year maintenance of equipment Binoculars in Costa Rica', 'fedbid', 'services_and_equipment', 1, 2, 1, '#BE90E3', 'it', '', 0.00, 'federal', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '2');
INSERT INTO `projects` VALUES (50026, 2, '2018-10-11', 'Computers 1830-17423_ADD1', 'RFQ project', 'Office Technology Products and Related Services', '2018-10-22 18:00:00', 'full_and_open', 'OEM services in Chicago, IL.', 'mail', 'services', 0, 2, 1, '#C7D0D3', 'it', '', 0.00, 'state', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '2');
INSERT INTO `projects` VALUES (50028, 2, '2018-10-12', 'VIDEO WALL SYSTEM', 'NA', 'VIDEO WALL SYSTEM', '2018-10-12 18:00:00', 'full_and_open', 'Video wall installation with Live wall Cayman Islands', 'email', 'services_and_equipment', 1, 4, 1, '#BE90E3', 'it', 'none', 0.00, 'state', 1, 0, 0, '2018-10-12', '0000-00-00', 1, 'E-Logic and Live wall Media installation of Video wall system and environment includes:\r\nInstallation\r\nConfiguration\r\nSet Up\r\nTesting\r\nTraining\r\n\r\nPlease make the follow exception to the payment terms in the Construction services agreement as follows:\r\n\r\n50% prior to shipment of product. Live Wall will provide photos of system in testing, photo of packaged system and waybill\r\n\r\n25% upon arrive of equipment at CUC\r\n\r\n15% upon completion of installation\r\n\r\n10% after 30-day burn in\r\n\r\nOptions:\r\nUpgrade to dual failover power supplies in displays - $6,000.00\r\nUpgrade to 3-year annual care maintenance - $11,107.8\r\n\r\n', '1', '21665.51', '', '', '', '2019-01-12', 'Caribbean Utilities Company, Ltd.\r\n457 North Sound Road\r\nP.O. Box 38 GT\r\nGrand Cayman, Cayman Islands\r\nAttention: Tony Hancock', 'Caribbean Utilities Company, Ltd.\r\n457 North Sound Road\r\nP.O. Box 38 GT\r\nGrand Cayman, Cayman Islands\r\nAttention: Tony Hancock', 157988.51, '2|4');
INSERT INTO `projects` VALUES (50029, 2, '2018-10-12', 'HMS Harvard', 'NA', 'Printer Repair for HMS Hardvard', '2018-10-12 18:00:00', 'full_and_open', 'Printer Repair', 'email', 'services', 1, 2, 1, '#BE90E3', 'it', 'none', 0.00, 'commercial', 1, 1, 1, '2018-10-12', '2018-10-22', 1, 'E-Logic Printer Repair Services ', '1', '90.00', '', '', '', '2019-01-12', '624 Maryland Ave NE, Washington DC 20002', '624 Maryland Ave NE, Washington DC 20002', 90.00, '2');
INSERT INTO `projects` VALUES (50030, 2, '2018-10-15', '39115', 'NA', 'Annapolis Rack Repair', '2018-10-15 18:00:00', 'full_and_open', 'quote for rack repair', 'email', 'services_and_equipment', 1, 2, 1, '#BE90E3', 'av', 'none', 0.00, 'federal', 1, 0, 0, '2018-10-15', '0000-00-00', 1, '', '', '11227.31', 'Miscellaneous|One Senior AV engineer to perform:\r\nProgramming \r\nInstallation\r\nIntegration \r\nRe-cabling\r\nSet Up\r\nTraining ', '1|40', '2504.03|8723.28', '2019-01-15', 'ANNAPOLIS NAVAL CLINIC. 695 KINKAID RD, ANNAPOLIS MD 21402.', 'ANNAPOLIS NAVAL CLINIC. 695 KINKAID RD, ANNAPOLIS MD 21402.', 38963.52, '2');
INSERT INTO `projects` VALUES (50031, 2, '2018-10-18', 'RFQ1336025', 'RFQ project', 'Classified Video Teleconferencing System', '2018-10-29 09:00:00', 'full_and_open', 'AV opportunity ', 'gsa', 'services_and_equipment', 1, 2, 1, '#BE90E3', 'av', 'none', 0.00, 'federal', 1, 0, 0, '2018-10-26', '0000-00-00', 1, 'One audio Visual Programmer and One Audio Visual Engineer to perform:\r\nProgramming\r\nInstallation \r\nIntegration\r\nSet Up \r\nTesting\r\nTraining\r\nE-Logic Services include 24/7 support with help desk platform, on-site response time and one preventive maintenance. \r\n', '1', '12502.6145', '', '', '', '2019-01-26', 'NSWC DAHLGREN\r\nRECEIVING OFFICER DAHLGREN DIV\r\n6220 TISDALE RD SUITE 159 BLDG 125\r\nDAHLGREN, VA  22448-5114', 'NSWC DAHLGREN\r\nRECEIVING OFFICER DAHLGREN DIV\r\n6220 TISDALE RD SUITE 159 BLDG 125\r\nDAHLGREN, VA  22448-5114', 76241.98, '2|4|6|7');
INSERT INTO `projects` VALUES (50034, 2, '2018-10-19', 'RFQ1335491', 'RFQ project', 'HARDWARE MAINTENANCE', '2018-10-19 00:00:00', 'full_and_open', 'Server Maintenance in FL', 'gsa', 'services', 0, 2, 1, '#C7D0D3', 'it', '', 0.00, 'federal', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '2');
INSERT INTO `projects` VALUES (50036, 4, '2018-10-19', 'W9124B-17-P-0048', 'NA', 'Fort Irwin', '2018-10-19 00:00:00', 'full_and_open', 'Brand name: N/A\r\nPart number: N/A\r\nItem description:\r\nSenior AV Design Engineer -\r\nResponse to service request: Codec connectivity and audio\r\nissue. Remote troubleshooting and diagnostic testing. Issue\r\nhas been remotely diagnosed by Lead AV Engineer.\r\n*On-site repair, after hours, approx. 12\r\nhours\r\n*Cable management and replacement\r\n*Upgrade', 'email', 'services', 1, 2, 1, '#BE90E3', 'av', 'none', 0.00, 'federal', 1, 1, 1, '2018-10-19', '2018-10-22', 1, 'Brand name: N/A\r\nPart number: N/A\r\nItem description:\r\nSenior AV Design Engineer -\r\nResponse to service request: Codec connectivity and audio\r\nissue. Remote troubleshooting and diagnostic testing. Issue\r\nhas been remotely diagnosed by Lead AV Engineer.\r\n*On-site repair, after hours, approx. 12\r\nhours\r\n*Cable management and replacement\r\n*Upgrade and reconfiguration of firmware\r\n*Testing', '1', '1440.05', 'Brand name: N/A\r\nPart number: N/A\r\nItem description:\r\nSenior AV Design Engineer -\r\nResponse to service request: Codec connectivity and audio\r\nissue. Remote troubleshooting and diagnostic testing. Issue\r\nhas been remotely diagnosed by Lead AV Engineer.\r\n*On-site repair, after hours, approx. 12\r\nhours\r\n*Cable management and replacement\r\n*Upgrade and reconfiguration of firmware\r\n*Testing', '12', '1440.05', '2019-01-19', 'W6DJ USA MSN SPT FT IRWIN\r\nAVE G REC WHSE BLDG. 934\r\nFORT IRWIN, CA 923 10- 5000\r\nPHONE: 760-380-6067\r\nATTN: Joseph Laughlin', 'W6DJ USA MSN SPT FT IRWIN\r\nAVE G REC WHSE BLDG. 934\r\nFORT IRWIN, CA 923 10- 5000\r\nPHONE: 760-380-6067\r\nATTN: Joseph Laughlin', 1440.05, '2|4');
INSERT INTO `projects` VALUES (50037, 2, '2018-10-22', '951700-18-F-0327', 'BBG', 'Broadcasting Board of Governors', '2018-10-22 18:00:00', 'full_and_open', 'BBG quote', 'email', 'services_and_equipment', 1, 2, 1, '#BE90E3', 'it', 'none', 0.00, 'federal', 1, 1, 1, '2018-10-22', '2018-11-05', 1, '', '', '160', 'Ticket Number: 83425\r\nTicket Problem: HP CP3505dn color printer not working\r\nE-Logic Repair:  Change the malfunctioning fuser, clean unit and test usage. \r\n\r\nEntered on 10/17/2018 at 2:48:30 PM EDT (GMT-0400) by Nicole Jimmerson:\r\nLeslie called back to report that the CP3505dn printer has a fuser error displayed on the screen.', '2', '160', '2019-01-22', 'Broadcasting Board of Governors\r\nSupervisor Computer Systems Support\r\nDivision\r\n330 Independence Ave SW, Room 4164C\r\nWashington, DC 20237', 'Juli Fletcher\r\nSupervisor Computer Systems Support\r\nDivision\r\n330 Independence Ave\r\nWashington, DC 20237', 505.00, '2');
INSERT INTO `projects` VALUES (50039, 2, '2018-10-23', 'RFQ1336271', 'RFQ project', '2nd generation information technology', '2018-11-07 16:00:00', 'sources_sought', 'RFI', 'gsa', 'services_and_equipment', 1, 4, 1, '#448AFF', 'it', '', 0.00, 'federal', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '2|4');
INSERT INTO `projects` VALUES (50040, 2, '2018-10-24', 'F2BDED8225B101', 'https://www.fbo.gov/index.php?s=opportunity&mode=form&tab=core&id=0651a8ad31d1f953a4833e0c6c0a7c1e&_cview=0', 'VTCs and AVSs for AFLCMC/HNAK', '2018-11-01 16:00:00', 'small_business', 'AV opportunity found by Dessire', 'email', 'services_and_equipment', 1, 2, 1, '#18D2F0', 'av', 'none', 0.00, 'federal', 1, 0, 0, '2018-11-01', '0000-00-00', 1, 'CLIN 1:\r\nE-Logic Audio Visual team and One Project Manager to perform:\r\nProgramming\r\nInstallation\r\nIntegration \r\nTesting \r\nTraining\r\nReporting \r\n\r\nOptional CLIN 2: $8,875.30\r\nOne (1) hour telephone response time for service calls M-F 8:00 AM to 6:00 PM ET.\r\nOnsite Response Time: 1 business day\r\nAnnual Preventive Maintenance: 2 Visits\r\nPM Services\r\nStaff Training: 2 Individuals\r\n\r\nCLIN3: $9,141.56\r\nOne (1) hour telephone response time for service calls M-F 8:00 AM to 6:00 PM ET.\r\nOnsite Response Time: 1 business day\r\nAnnual Preventive Maintenance: 2 Visits\r\nPM Services\r\nStaff Training: 2 Individuals\r\n\r\nCLIN 4: $9,415.81\r\nOne (1) hour telephone response time for service calls M-F 8:00 AM to 6:00 PM ET.\r\nOnsite Response Time: 1 business day\r\nAnnual Preventive Maintenance: 2 Visits\r\nPM Services\r\nStaff Training: 2 Individuals', '1', '27967.091', '', '', '', '2019-02-01', '9 Eglin Street\r\nHanscom AFB, Massachusetts 01731 \r\nUnited States ', '5 Eglin Street, Bldg. 1624\r\nHanscom AFB, Maryland 01731 \r\nUnited States', 318487.86, '2|4|6|7');
INSERT INTO `projects` VALUES (50042, 2, '2018-10-25', '70RDAD18Q00000080', 'NA', 'Department of Homeland Security A/V Support', '2018-10-25 18:00:00', 'full_and_open', 'DHS quote', 'email', 'services_and_equipment', 1, 2, 1, '#BE90E3', 'av', 'none', 0.00, 'federal', 1, 0, 0, '2018-10-25', '0000-00-00', 1, 'One A/V engineer to perform: \r\nInstallation\r\nProgramming\r\nConfiguration', '4', '473.31', '', '', '', '2019-01-25', 'U.S. Dept. of Homeland Security\r\nOffice of Procurement Operations\r\nDept. Operations Acquisition Div.\r\n245 Murray Lane, SW, #0115\r\nWashington DC 20528-0115', 'Attn: Harry Browner\r\n245 Murray Lane SW\r\nMGMT/OCPO/AWT-Mail Stop 0085\r\nWashington DC 20528-0085\r\n', 3236.09, '2');
INSERT INTO `projects` VALUES (50044, 2, '2018-10-31', '951700-18-F-0327', 'BBG', 'BBG quote', '2018-10-31 18:00:00', 'full_and_open', 'BBG quote', 'email', 'services_and_equipment', 1, 2, 1, '#BE90E3', 'av', 'none', 0.00, 'federal', 1, 1, 1, '2018-10-31', '2018-11-05', 1, 'Ticket Number: 83688\r\nTicket Problem: iMac hard drive is bad\r\nE-Logic Repair: Pick up and drop off. \r\nEntered on 10/22/2018 at 2:27:47 PM EDT (GMT-0400) by Nicole Jimmerson:\r\nFrom: Hung Van \r\nSent: Sunday, October 21, 2018 7:35 PM\r\nTo: Nicole A. Jimmerson <njimmerson@usagm.gov>\r\nSubject: Fwd: iMac\r\n\r\nHi Nicole,\r\nPlease proceed the ticket.\r\nThanks\r\nVan', '2', '160', '', '', '', '2019-01-31', 'Broadcasting Board of Governors\r\nSupervisor Computer Systems Support\r\nDivision\r\n330 Independence Ave SW, Room 4164C\r\nWashington, DC 20237', 'Juli Fletcher\r\nSupervisor Computer Systems Support\r\nDivision\r\n330 Independence Ave\r\nWashington, DC 20237', 431.05, '2');
INSERT INTO `projects` VALUES (50045, 2, '2018-11-05', '', 'https://www.fbo.gov/index?s=opportunity&mode=form&tab=core&id=38af2adc3142b54f398d05b89c1f5c4f', '', '0000-00-00 00:00:00', '', '', '', '', 0, 2, 0, '', '', '', 0.00, '', 0, 0, 0, '0000-00-00', '0000-00-00', 1, '', '', '', '', '', '', '0000-00-00', '', '', 0.00, '2|4');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `services`
-- 

CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_project` int(11) NOT NULL,
  `total_service` decimal(20,2) DEFAULT NULL,
  `total_equipment` decimal(20,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_project` (`id_project`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

-- 
-- Volcar la base de datos para la tabla `services`
-- 

INSERT INTO `services` VALUES (1, 50000, 7824688.40, 0.00);
INSERT INTO `services` VALUES (5, 50004, 0.00, 0.00);
INSERT INTO `services` VALUES (6, 50005, 0.00, 0.00);
INSERT INTO `services` VALUES (7, 50006, 0.00, 0.00);
INSERT INTO `services` VALUES (8, 50007, 0.00, 0.00);
INSERT INTO `services` VALUES (9, 50008, 0.00, 0.00);
INSERT INTO `services` VALUES (10, 50009, 0.00, 0.00);
INSERT INTO `services` VALUES (11, 50010, 0.00, 0.00);
INSERT INTO `services` VALUES (12, 50011, 0.00, 0.00);
INSERT INTO `services` VALUES (13, 50012, 0.00, 0.00);
INSERT INTO `services` VALUES (14, 50013, 0.00, 0.00);
INSERT INTO `services` VALUES (15, 50014, 0.00, 0.00);
INSERT INTO `services` VALUES (17, 50016, 0.00, 0.00);
INSERT INTO `services` VALUES (18, 50017, 0.00, 0.00);
INSERT INTO `services` VALUES (22, 50021, 0.00, 0.00);
INSERT INTO `services` VALUES (23, 50022, 756.89, 0.00);
INSERT INTO `services` VALUES (26, 50025, 0.00, 0.00);
INSERT INTO `services` VALUES (27, 50026, 0.00, 0.00);
INSERT INTO `services` VALUES (29, 50028, 21665.51, 136323.00);
INSERT INTO `services` VALUES (30, 50029, 90.00, 0.00);
INSERT INTO `services` VALUES (31, 50030, 11227.31, 27736.21);
INSERT INTO `services` VALUES (32, 50031, 12502.61, 63739.37);
INSERT INTO `services` VALUES (35, 50034, 0.00, 0.00);
INSERT INTO `services` VALUES (37, 50036, 1440.05, 0.00);
INSERT INTO `services` VALUES (38, 50037, 160.00, 345.00);
INSERT INTO `services` VALUES (40, 50039, 0.00, 0.00);
INSERT INTO `services` VALUES (41, 50040, 27967.09, 290520.77);
INSERT INTO `services` VALUES (43, 50042, 473.31, 2762.78);
INSERT INTO `services` VALUES (45, 50044, 160.00, 271.05);
INSERT INTO `services` VALUES (46, 50045, 0.00, 0.00);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `staff`
-- 

CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_service` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hourly_rate` decimal(20,2) DEFAULT NULL,
  `rate` decimal(20,2) DEFAULT NULL,
  `office_expenses` decimal(20,2) DEFAULT NULL,
  `burdened_rate` decimal(20,2) DEFAULT NULL,
  `fblr` decimal(20,2) DEFAULT NULL,
  `hours_project` int(11) DEFAULT NULL,
  `total_burdened_rate` decimal(20,2) DEFAULT NULL,
  `total_fblr` decimal(20,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_service` (`id_service`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

-- 
-- Volcar la base de datos para la tabla `staff`
-- 

INSERT INTO `staff` VALUES (1, 1, 'FIELD ENGINEER (AUDIO/VISUAL)', 32.50, 5.00, 2200.00, 48.60, 48.96, 1920, 93311.78, 94011.62);
INSERT INTO `staff` VALUES (2, 1, 'CRESTRON/AMX PROGRAMMER', 55.00, 5.00, 2200.00, 80.29, 80.89, 1920, 154157.17, 155313.35);
INSERT INTO `staff` VALUES (3, 1, 'AUTOCAD DESIGNER', 26.50, 5.00, 2200.00, 40.15, 40.45, 1920, 77086.35, 77664.49);
INSERT INTO `staff` VALUES (4, 1, 'AV SYSTEMS DESIGN ENGINEER,  LEVEL 1', 69.00, 5.00, 2200.00, 100.01, 100.76, 1920, 192016.53, 193456.65);
INSERT INTO `staff` VALUES (5, 1, 'AV SYSTEMS DESIGN ENGINEER, LEVEL II', 83.50, 5.00, 2200.00, 120.43, 121.33, 1920, 231228.00, 232962.21);
INSERT INTO `staff` VALUES (6, 1, 'ON-SITE TRAINER', 54.50, 5.00, 2200.00, 79.59, 80.18, 1920, 152805.05, 153951.09);
INSERT INTO `staff` VALUES (7, 1, 'VIDEO NETWORK ENGINEER', 36.50, 5.00, 2200.00, 54.23, 54.64, 1920, 104128.74, 104909.71);
INSERT INTO `staff` VALUES (8, 1, 'PROJECT MANAGER (PM)', 81.80, 5.00, 2200.00, 118.04, 118.92, 1920, 226630.79, 228330.52);
INSERT INTO `staff` VALUES (9, 1, 'ELECTRONICS TECHNICIAN III', 35.70, 5.00, 2200.00, 53.11, 53.51, 1920, 101965.35, 102730.09);
INSERT INTO `staff` VALUES (10, 1, 'ELECTRONICS TECHNICIAN II', 27.40, 5.00, 2200.00, 41.42, 41.73, 1920, 79520.16, 80116.56);
INSERT INTO `staff` VALUES (11, 1, 'ELECTRONICS TECHNICIAN I', 20.50, 5.00, 2200.00, 31.70, 31.94, 1920, 60860.91, 61317.37);
INSERT INTO `staff` VALUES (12, 23, 'Fernando Thames', 23.00, 5.00, 2200.00, 35.22, 35.48, 20, 704.39, 709.67);
INSERT INTO `staff` VALUES (17, 29, 'Installation E-Logic', 23.00, 10.00, 2200.00, 36.78, 37.05, 40, 1471.12, 1482.15);
INSERT INTO `staff` VALUES (18, 29, 'Installation Live Wall', 250.50, 0.00, 2200.00, 339.91, 342.46, 40, 13596.29, 13698.26);
INSERT INTO `staff` VALUES (19, 29, 'Installation Local Contractor', 100.00, 5.00, 2200.00, 143.67, 144.75, 24, 3448.10, 3473.96);
INSERT INTO `staff` VALUES (20, 30, 'Fernando Thames', 23.00, 5.00, 2200.00, 35.22, 35.48, 1, 35.22, 35.48);
INSERT INTO `staff` VALUES (21, 31, 'Miscellaneous ', 1859.00, 0.00, 0.00, 2504.03, 2522.81, 1, 2504.03, 2522.81);
INSERT INTO `staff` VALUES (22, 31, 'Ali Pacheco', 160.00, 0.00, 2200.00, 218.08, 219.72, 40, 8723.28, 8788.70);
INSERT INTO `staff` VALUES (23, 11, 'NA', 0.00, 0.00, 2200.00, 2.70, 2.72, 0, 0.00, 0.00);
INSERT INTO `staff` VALUES (26, 37, 'Senior AV Design Engineer', 87.99, 0.00, 0.00, 120.00, 120.90, 12, 1440.05, 1450.85);
INSERT INTO `staff` VALUES (27, 38, 'Fer', 80.00, 0.00, 0.00, 80.00, 80.00, 2, 160.00, 160.00);
INSERT INTO `staff` VALUES (28, 43, 'Ali Pacheco', 40.00, 5.00, 2200.00, 59.16, 59.61, 8, 473.31, 476.86);
INSERT INTO `staff` VALUES (31, 32, 'Ali Pacheco', 49.97, 5.00, 2200.00, 73.21, 73.75, 80, 5856.45, 5900.38);
INSERT INTO `staff` VALUES (32, 32, 'Dessire Flores', 23.44, 5.00, 2200.00, 35.84, 36.11, 40, 1433.57, 1444.32);
INSERT INTO `staff` VALUES (33, 32, 'Help Desk India', 7.50, 5.00, 2200.00, 13.39, 13.49, 112, 1499.50, 1510.75);
INSERT INTO `staff` VALUES (34, 45, 'Fernando Thames', 80.00, 0.00, 0.00, 80.00, 80.00, 2, 160.00, 160.00);
INSERT INTO `staff` VALUES (35, 41, 'Ali Pacheco', 49.97, 5.00, 2200.00, 73.21, 73.75, 120, 8784.68, 8850.56);
INSERT INTO `staff` VALUES (36, 41, 'Dessire Flores', 23.44, 5.00, 2200.00, 35.84, 36.11, 120, 4300.71, 4332.97);
INSERT INTO `staff` VALUES (37, 41, 'AV Technician', 23.44, 5.00, 2200.00, 35.84, 36.11, 120, 4300.71, 4332.97);
INSERT INTO `staff` VALUES (38, 41, 'AV Technician', 23.44, 5.00, 2200.00, 35.84, 36.11, 120, 4300.71, 4332.97);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tasks`
-- 

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_project` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `designated_user` int(11) NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `completed` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_project` (`id_project`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- 
-- Volcar la base de datos para la tabla `tasks`
-- 

INSERT INTO `tasks` VALUES (1, 50000, 2, 7, '2018-10-04', 'Technical Information ', 1);
INSERT INTO `tasks` VALUES (8, 50031, 2, 7, '2018-10-24', 'please provide technical and list of equipment', 1);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `users`
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `names` varchar(100) NOT NULL,
  `last_names` varchar(100) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- 
-- Volcar la base de datos para la tabla `users`
-- 

INSERT INTO `users` VALUES (1, 'raul93611', '$2y$10$WyHo8PYXaVLpLSj9elR2P.ERz/LV1QyaPVwUU7.EDn8qBZ/jCgxzm', 'leonardo', 'velasco', 1, 'lvelasco@e-logic.us', 1);
INSERT INTO `users` VALUES (2, 'arollano', '$2y$10$yr41IIzvov.b7KG6xyq4demLKwn9JWdSw5EE9sUHMo0duObLjqk5W', 'Andres', 'Rollano', 3, 'arollano@e-logic.us', 1);
INSERT INTO `users` VALUES (3, 'lpadilla', '$2y$10$d1obhT2uQo/fgKjJaGj3ueLZTuL.TnS88ACbdIBfjECJ.6gdesr1m', 'Luis', 'Padilla', 2, 'lpadilla@e-logic.us', 1);
INSERT INTO `users` VALUES (4, 'jbrillantes', '$2y$10$Y7a60gNRlVw7Wh2NqhINHOn6rZHdyQTDAZAstyJz11cFCkBFtR0kC', 'Joseph', 'Brillantes', 4, 'jbrillantes@e-logic.us', 1);
INSERT INTO `users` VALUES (5, 'bfoster', '$2y$10$DVT/dQJ4t8PR5biSPJI3aejyTTjCl86TYAJpUQOxPKJ6MorTiiXTS', 'Brittany', 'Foster', 4, 'bfoster@e-logic.us', 1);
INSERT INTO `users` VALUES (6, 'apacheco', '$2y$10$S7hTqqw4v.8RIpKy5yGm7OGBhl4cEMIsxWBljf.G8hbTR0z0hOCWy', 'Ali', 'Pacheco', 5, 'apacheco@e-logic.us', 1);
INSERT INTO `users` VALUES (7, 'dflores', '$2y$10$8Kvxfya3belc4BhSLmqoneIM5tDVB1/WpCMReZEQcIDNzlxRyAuli', 'Dessire', 'Flores', 5, 'dflores@e-logic.us', 1);
INSERT INTO `users` VALUES (8, 'fthames', '$2y$10$z/N34Vn.aUWKe5YnAbB0GOj7fVJqOuuyAEjMYqLY/scAn1iCJ/FFi', 'Fernando', 'Thames', 5, 'fthames@e-logic.us', 1);
INSERT INTO `users` VALUES (9, 'laura', '$2y$10$/TiFaqcpZfWcbDycTrQV8ueW/MCadK1ZygRmUwSvCFdOl.fZXEC1m', 'Laura', 'Villafan', 2, 'lvillafan@e-logic.us', 1);
INSERT INTO `users` VALUES (10, 'mHuapalla', '$2y$10$glaHE8txXb6JxogVrMewIOz5ObVB6tE3H30X6WJ1XKVCYHuoXFS2i', 'Marcy', 'Huapalla', 2, 'mhuapalla@e-logic.us', 1);
INSERT INTO `users` VALUES (11, 'cecilia', '$2y$10$Kuv4y8ejtRuSqPz4u30CZ.gfPQotW973HXGE76S8Ip5jYw7t5Ukg6', 'Cecilia', 'Peredo', 2, 'cperedo@e-logic.us', 1);

-- 
-- Filtros para las tablas descargadas (dump)
-- 

-- 
-- Filtros para la tabla `comments`
-- 
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_project`) REFERENCES `projects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

-- 
-- Filtros para la tabla `costs`
-- 
ALTER TABLE `costs`
  ADD CONSTRAINT `costs_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `services` (`id`) ON UPDATE CASCADE;

-- 
-- Filtros para la tabla `projects`
-- 
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

-- 
-- Filtros para la tabla `services`
-- 
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`id_project`) REFERENCES `projects` (`id`) ON UPDATE CASCADE;

-- 
-- Filtros para la tabla `staff`
-- 
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `services` (`id`) ON UPDATE CASCADE;

-- 
-- Filtros para la tabla `tasks`
-- 
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`id_project`) REFERENCES `projects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

<?php require_once("siteadmin/includes/initialize.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>M3H</title>
<link href="css/stylesheet.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
	function toggleDisplay(objMain) {
		//alert(objMain)
		var objImg = document.getElementById(objMain);

		objImg.src = objImg.src.replace("images/minus.gif", "images/plus.gif");
	}

	function chldFeedback(){
		window.open("/online/WEB/static_pages/feedback.jsp",'', 'toolbar=no,location=no,status=no,scrollbars=yes,menubar=no,width=780,height=680,left=50,top=10');
		return false;
	}

	function toggleDisplay2(objMain) {

		var objImg = document.getElementById(objMain);
		if (objImg.src.search("images/plus.gif") > 0) {
			objImg.src = objImg.src.replace("images/plus.gif",
					"images/minus.gif");

		} else {
			objImg.src = objImg.src.replace("images/minus.gif",
					"images/plus.gif");
		}
	}
	function fireonClick(aName) {
		for ( var x = 1; x <= 9; x++) {
			if (aName == "a" + x) {
			} else {
				document.getElementById("a" + x).style.display = 'none';

				//   toggleDisplay("q"+x);
			}
		}

		//  var title = document.getElementById(tName);
		//  if (title == null) return;
		var ans = document.getElementById(aName);
		//   if (ans == null) return;
		//  var que = document.getElementById(qName);
		if (ans.style.display == '') {
			//     if (que != null) que.style.display = 'none';
			ans.style.display = 'none';

		} else {
			//      if (que != null) que.style.display = '';
			ans.style.display = '';
		}

	}
</script>
</head>

<body>
<div class="wrapper">
  <?php include("header.php"); ?>
  
  <div class="bread_crump">
                <a href="#">Home</a> &raquo; <a href="#">My Webrex.com</a> &raquo; 
                Faq
        </div><!--bread_crump--> 
  
  <div class="clear"></div>
  
   <div class="inner-container">
       <div class="faq-container">
          <h2>FAQ's ON POST LISTING </h2>
          
          <div class="faq">
          
         <table class="table" align="center" border="0" cellpadding="1" cellspacing="1" width="100%">
          <tbody>
           <tr>
            <td height="20" align="left" bgcolor="#F5F5F5" width="14%"><strong class="FontHeader">Question 1 </strong></td>
            <td align="left" bgcolor="#F5F5F5" width="86%"><a href="javascript:;" onclick="fireonClick('a1')">What is Yolist?</a></td>
           </tr>
           
            <tr id="a1" style="">
              <td valign="top" width="14%"><span class="BlueHeader">Answer :</span></td>
                <td width="86%">Yolist.com is fastest growing classified 
                portal in India, promoted by The Times of India group, the largest media
                &amp; entertainment conglomerate in the country. At Yolist.com, you can
                post your buy or sell requirements for FREE and browse through the ads 
                posted by other people on site or on Times of India Newspaper.</td>
            </tr>


            <tr>
              <td height="20" align="left" bgcolor="#F5F5F5" valign="top"><strong class="FontHeader">Question 2 </strong></td>
              <td align="left" bgcolor="#F5F5F5"><a href="javascript:;" onclick="fireonClick('a2')">How can I post an ad on 
              Yolist?</a></td>
            </tr>
           
            <tr id="a2" style="display: none;">
              <td height="26" valign="top" width="14%"><span class="BlueHeader">Answer :</span></td>
              <td width="86%">
                <p>Ad posting on yolist.com is very simple. Just click on 
                Post Free Classified Ads link from top navigation of every page and give
                information about your requirement or offering.<br>
				You can follow these simple guidelines to make your ad more effective:</p>
              	<ul>
              		<li>Give as much as information possible related to your ad to get maximum responses.</li>
              		<li>Choose appropriate category &amp; nearest possible location.</li>
              		<li>Provide genuine contact details.</li>
              	</ul>
              </td>
            </tr>
            
            <tr>
              <td height="20" align="left" bgcolor="#F5F5F5"><strong class="FontHeader">Question 3 </strong></td>
              <td align="left" bgcolor="#F5F5F5"><a href="javascript:;" onclick="fireonClick('a3')">Does online ad posting on
               Yolist is paid?</a></td>
            </tr>
            <tr id="a3" style="display: none;">
               <td valign="top" width="14%"><span class="BlueHeader">Answer :</span></td>
               <td width="86%">No, Ad posting on Yolist.com is absolutely free. Go ahead and make full use of it!</td>
            </tr>
            
            <tr>
              <td height="20" align="left" bgcolor="#F5F5F5" valign="top"><strong class="FontHeader">Question 4 </strong></td>
              <td align="left" bgcolor="#F5F5F5"><a href="javascript:;" onclick="fireonClick('a4')">Can I browse Times of 
              India Classifieds Ad?</a></td>
            </tr>
            
            <tr id="a4" style="display: none;">
               <td valign="top" width="14%"><span class="BlueHeader">Answer :</span></td>
                <td width="86%">You can browse and send responses to all 
                Times of India Classifieds Ad for FREE. hidden and users will send their
                responses using an online form that will be diverted to your email.</td>
            </tr>
            
            <tr>
              <td height="20" align="left" bgcolor="#F5F5F5" valign="top"><strong class="FontHeader">Question 5 </strong></td>
              <td align="left" bgcolor="#F5F5F5"><a href="javascript:;" onclick="fireonClick('a5')">How can I search for
               particular product / services?</a></td>
            </tr>
            
            <tr id="a5" style="display: none;">
               <td valign="top" width="14%"><span class="BlueHeader">Answer :</span></td>
                    <td width="86%">You can search for a particular product 
                    or service by just typing it in search box given on top of each page. 
                    You can also choose category or location of your choice.</td>
            </tr>            
            
            <tr>
              <td height="20" align="left" bgcolor="#F5F5F5" valign="top"><strong class="FontHeader">Question 6 </strong></td>
              <td align="left" bgcolor="#F5F5F5"><a href="javascript:;" onclick="fireonClick('a6')">How do I reply to an Ad?</a></td>
            </tr>
            
            <tr id="a6" style="display: none;">
               <td valign="top" width="14%"><span class="BlueHeader">Answer :</span></td>
                    <td width="86%">Click on the ad title which is of your 
                    interest from search results. On Ad Detail Page, in Right hand side, you
                    will find a section - 'Send Instant Enquiry'. Fill your details like 
                    email, name, and message to the advertiser and submit, instantly a mail 
                    will be send to advertiser with your contact details.
                    Additionally, you can call/ SMS/ fax/ post to the advertisers if they 
                    have given any contact details.</td>
            </tr>
            
            <tr>
              <td height="20" align="left" bgcolor="#F5F5F5" valign="top"><strong class="FontHeader">Question 7 </strong></td>
              <td align="left" bgcolor="#F5F5F5"><a href="javascript:;" onclick="fireonClick('a7')">How long does my ad remain
              posted on Yolist?</a></td>
            </tr>
            
            <tr id="a7" style="display: none;">
               <td valign="top" width="14%"><span class="BlueHeader">Answer :</span></td>
               <td width="86%">Your ad willobe live on site for 60 days and you can enjoy the responses.</td>

            </tr> 
             

            <tr>
              <td height="20" align="left" bgcolor="#F5F5F5" valign="top"><strong class="FontHeader">Question 8 </strong></td>
              <td align="left" bgcolor="#F5F5F5"><a href="javascript:;" onclick="fireonClick('a8')">Whom to contact regarding 
              any other questions, problems, or suggestions that I have?</a></td>
            </tr>
            
            <tr id="a8" style="display: none;">
               <td valign="top" width="14%"><span class="BlueHeader">Answer :</span></td>
                <td width="86%">You can also contact us at: <a href="http://www.yolist.com/feedback" target="_blank">
                <strong>http://www.yolist.com/feedback</strong></a>.
                We will be more than happy to hear from you and address your issues at 
                the earliest. Your valuable feedback and suggestions are also welcome.</td>
            </tr>              

            <tr>
              <td height="20" align="left" bgcolor="#F5F5F5" valign="top"><strong class="FontHeader">Question 9 </strong></td>
              <td align="left" bgcolor="#F5F5F5"><a href="javascript:;" onclick="fireonClick('a9')">How do I know if my ad
               has been posted on Yolist.com?</a></td>
            </tr>
            
            <tr id="a9" style="display: none;">
               <td valign="top" width="14%"><span class="BlueHeader">Answer :</span></td>
                <td width="86%">Once your ad is approved or rejected. You
                would get a mail from us regarding the status of your ad with ad id. 
                Please specify the unique Ad ID of your ad (if allotted to you already) 
                or give reference of your ad to would help us in your quick help.</td>
            </tr>
         </tbody>
       </table>
        <br class="clear">
    </div><!--faq-->


    <h3>Print Ad Tips</h3>
    
    <p>Users are recommended to make all enquiries and seek appropriate advice before acting on any print advertisement
     appearing on yolist.com. Any person sending money, incurring, and expenses or acting on any medical recommendations or 
     entering into any commitment in relation to any advertisement published in this publication, shall do so entirely at 
     his/her discretion, intelligence and risk. The Company, Publisher or any of its employees do not vouch for any claims made 
     by the Advertisers of products and services shall not be held liable for any damages, loss, consequences, suffered by any 
     person on account of relying on such advertisements.</p>
     
    <h3>SCAM Avoiding Tips</h3>
    
    <ul>
   
    <li>Try to deal with local people whom you can meet in person.</li>
    <li>Yolist.com is never involved in any financial and legal transactions.</li>
    <li>Never disclose your financial details like bank account number, PIN no.s etc.</li>

    </ul>




       </div><!--faq-container-->
       
       <div class="clear"></div>
    </div><!--inner-container-->
    
    <div class="featured-ads">
    <div class="ads-left">
      <img src="images/ad1.jpg" />
    </div><!--ads-left-->
    
    <div class="ads-right">
      <img src="images/ad2.jpg" />
    </div><!--ads-right-->
    
    <div class="clear"></div>
  </div><!--featured-ads-->
  
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>

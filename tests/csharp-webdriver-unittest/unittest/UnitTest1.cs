using System;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using OpenQA.Selenium;
using OpenQA.Selenium.Support.UI;
using OpenQA.Selenium.Chrome;

namespace unittest
{
    [TestClass]
    public class UnitTest1
    {
        [TestMethod]
        public void TestLogin()
        {
            try
            {
                PageLogin pageLogin = new PageLogin();

                pageLogin.open();
                PageMain pageMain = pageLogin.login("test", "1");

                pageMain.checkDisplayed();

                Assert.AreEqual(1,1);
            }
            catch(Exception e)
            {
                Assert.Fail(e.ToString());
            }
        }
    }
}

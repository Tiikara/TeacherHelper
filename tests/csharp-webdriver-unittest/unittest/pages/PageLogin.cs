using OpenQA.Selenium;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace unittest
{
    class PageLogin : PageObject
    {
        public void open()
        {
            webdriver.Url = "http://localhost/teacher/";
            webdriver.Navigate();
        }

        public PageMain login(string name, string password)
        {
            webdriver.FindElement(By.Id("login")).SendKeys(name);
            webdriver.FindElement(By.Id("password")).SendKeys(password);
            webdriver.FindElement(By.ClassName("login-button")).Click();

            return new PageMain();
        }

    }
}

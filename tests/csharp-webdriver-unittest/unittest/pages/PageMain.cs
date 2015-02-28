using OpenQA.Selenium;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace unittest
{
    class PageMain : PageObject
    {
        public void checkDisplayed()
        {
            webdriver.FindElement(By.ClassName("hello"));

        }
    }
}

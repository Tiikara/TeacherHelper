using OpenQA.Selenium;
using OpenQA.Selenium.Chrome;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace unittest
{
    class PageObject
    {



        static protected IWebDriver webdriver = new ChromeDriver("C:/WebServers/home/localhost/www/teacher/tests/csharp-webdriver-unittest/webdrivers");
    }
}

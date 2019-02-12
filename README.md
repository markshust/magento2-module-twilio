<h1 align="center">MarkShust_Twilio</h1> 

<div align="center">
  <p>Sends SMS messages in response to Magento events</p>
  
  <img src="https://img.shields.io/badge/magento-2.2%20|%202.3-brightgreen.svg?logo=magento&longCache=true&style=flat-square" alt="Supported Magento Versions" />
  <a href="https://packagist.org/packages/markshust/magento2-module-twilio" target="_blank"><img src="https://img.shields.io/packagist/v/markshust/magento2-module-twilio.svg?style=flat-square" alt="Latest Stable Version" /></a>
  <a href="https://packagist.org/packages/markshust/magento2-module-twilio" target="_blank"><img src="https://poser.pugx.org/markshust/magento2-module-twilio/downloads" alt="Composer Downloads" /></a>
  <a href="https://GitHub.com/Naereen/StrapDown.js/graphs/commit-activity" target="_blank"><img src="https://img.shields.io/badge/maintained%3F-yes-brightgreen.svg?style=flat-square" alt="Maintained - Yes" /></a>
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>

</div>

## Table of contents

- [Summary](#summary)
- [Installation](#installation)
- [Usage](#usage)
- [License](#license)

## Summary

There may be situations where you would like to be notified by SMS when a specific action happens within Magento. This module integrates with Twilio to provide that ability.

Currently implemented is a "pick & pack" SMS order notification which is sent in response to an order that has been placed.

## Installation

```
composer require markshust/magento2-module-twilio
bin/magento module:enable MarkShust_Twilio
bin/magento setup:upgrade
```

Retrieve your Account SID and Auth Token from the <a href="https://www.twilio.com/console" target="_blank">Twilio Console</a> Account Dashboard.

Enable the module and add Twilio credentials at Admin > Stores > Configuration > Twilio > General Configuration.

## Usage

Placing an order will result in an SMS message being sent:

![Screenshot](https://raw.githubusercontent.com/markshust/magento2-module-twilio/master/docs/demo.png)

## License

[MIT](https://opensource.org/licenses/MIT)

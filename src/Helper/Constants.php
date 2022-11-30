<?php

namespace App\Helper;


class Constants
{
   /* Write the constate name all With Capital Letter and underscore
   * ex.
   * const ACTIVE_USER_STATUS=1
   */

/* Bed Status accessibility  */
const FREE_BED = 0;
const OCCUPIED_BED =1;
/* Bed Status current status  */
const NON_IMPENDING =2;
const IMPENDING =1;
/* Bed Functionality  */
const FUNCTIONAL = 0;
const NONFUNCTIONAL = 1;
/* User and group status */
const ACTIVATED=1;
const INACTIVE=0;
/* Appointment status */
const OPEN=1;
const CLOSED=2;
/* Patient's adminssion status  */
const REGISTERED=0;
const ADMITTED=1;
const WAITING =2;
const RE_ADMITTED=3;
const DISCHARGED=4;
/* ADMISSION CONSTANTS */
const ADMISSION_ADMITTED   = 0;
const ADMISSION_DISCHARGED = 1;

/* user online status */

const ONLINE=1;
const OFFLINE=0;
}

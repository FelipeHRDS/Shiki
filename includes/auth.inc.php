<?php

# These classes have access to every user's invoice history and can register new users
$privilegedClasses = ['admin', 'supervisor'];

# These classes have a lower limit of only R$100,00 on the total cost of their invoices
$noLowerLimitClasses = ['jun' , 'fabiodarc', 'tatianedasilva', 'matsuya', 'ragun'];

# These classes are 'representative users', which means they can put in invoices for other companies under their representation
# This will probably only contain 'rep'
$repUsers = ['rep'];
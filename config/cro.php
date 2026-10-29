<?php

// Interactive CRO sections (partials/cro/*). Each page gets an "industry" from its URL,
// and the widgets read their wording from here. Calculators only use the visitor's own numbers.
return [

    // URL keyword => industry (first match wins)
    'match' => [
        'salon|spa|barber|nail|beauty|appointment|gym' => 'salon',
        'pharmacy|hospital|medical' => 'pharmacy',
        'restaurant|food|cafe|coffee|bakery|cafeteria|delivery|dining|table-management|hotel|restro|menu' => 'restaurant',
        'grocery|supermarket|gas-station|dates' => 'grocery',
        'gold|jewel' => 'gold',
        'laundry|tailor|car-wash|service-business' => 'service',
        'accounting|payroll|employee|erp|distribution|van-sales|invoic|business-size|e-invoicing' => 'office',
    ],
    'default' => 'retail',

    'industries' => [

        'retail' => [
            'business' => 'Retail Shop',
            'place' => 'store',
            'modules' => ['inventory', 'vat', 'loyalty', 'online'],
            'calculator' => [
                'tag' => 'Stock Loss Calculator',
                'title' => 'How much stock quietly disappears each year?',
                'intro' => 'Errors, theft and counts that never match add up. Move the sliders to your own numbers.',
                'formula' => 'percent_of_value',
                'sliders' => [
                    ['label' => 'Stock value you hold', 'min' => 10000, 'max' => 2000000, 'step' => 10000, 'value' => 200000, 'prefix' => 'AED '],
                    ['label' => 'Lost to errors, theft & mismatches each year', 'min' => 0.5, 'max' => 10, 'step' => 0.5, 'value' => 2, 'suffix' => '%'],
                ],
                'result' => 'Stock you could be losing every year',
                'fix' => 'Real-time stock, barcode billing and staff permissions show exactly where it goes.',
            ],
            'quiz' => [
                'Do customers wait in a queue at busy times?',
                'Does the stock on your system often differ from the shelf?',
                'Do you add up sales or VAT by hand at the end of the day?',
                'Is it hard to see which products actually make you money?',
            ],
            'day' => [
                ['08:45', 'Reorder list ready', 'Low-stock alerts show what to order before you open.'],
                ['11:30', 'Busy hour, short queue', 'Barcode billing and quick keys keep the counter moving.'],
                ['13:15', 'A regular comes back', 'Loyalty points and purchase history on every bill.'],
                ['16:00', 'Branch 2 runs out', 'Move stock between branches instead of losing the sale.'],
                ['19:30', 'A return at the counter', 'Find the original invoice and refund the right amount.'],
                ['22:00', 'Day closed', 'Sales, stock and VAT summary in one report.'],
            ],
        ],

        'grocery' => [
            'business' => 'Supermarket / Grocery',
            'place' => 'supermarket',
            'modules' => ['inventory', 'vat', 'loyalty', 'mobile'],
            'calculator' => [
                'tag' => 'Shrinkage Calculator',
                'title' => 'What do expiry, waste and shrinkage cost you?',
                'intro' => 'Fresh stock, wrong prices and missed scans add up fast in a supermarket. Use your own numbers.',
                'formula' => 'percent_of_value',
                'sliders' => [
                    ['label' => 'Stock value on your shelves', 'min' => 20000, 'max' => 5000000, 'step' => 20000, 'value' => 400000, 'prefix' => 'AED '],
                    ['label' => 'Lost to expiry, waste & shrinkage each year', 'min' => 0.5, 'max' => 10, 'step' => 0.5, 'value' => 3, 'suffix' => '%'],
                ],
                'result' => 'Stock you could be losing every year',
                'fix' => 'Expiry alerts, scale integration and real-time stock keep it under control.',
            ],
            'quiz' => [
                'Do queues build up at the checkout at peak times?',
                'Do price changes take long to reach every counter?',
                'Do you throw away stock that expired on the shelf?',
                'Do you add up sales or VAT by hand at the end of the day?',
            ],
            'day' => [
                ['07:30', 'New prices everywhere', 'Price changes reach every counter before the doors open.'],
                ['10:00', 'Fresh counter', 'Weighed items billed straight from the scale label.'],
                ['12:00', 'Weekend offer', 'Promotions apply on their own at the till.'],
                ['17:30', 'Evening rush', 'Several lanes billing at once, stock updating live.'],
                ['20:00', 'Shelf check', 'Low-stock and near-expiry lists for tomorrow.'],
                ['23:00', 'Day closed', 'Sales, stock and VAT summary in one report.'],
            ],
        ],

        'restaurant' => [
            'business' => 'Restaurant / Cafe',
            'place' => 'restaurant',
            'modules' => ['kitchen', 'inventory', 'vat', 'loyalty'],
            'calculator' => [
                'tag' => 'Food Waste Calculator',
                'title' => 'How much of your food cost ends up in the bin?',
                'intro' => 'Over-ordering, waste and stock nobody can account for. Put in your own monthly numbers.',
                'formula' => 'percent_of_monthly',
                'sliders' => [
                    ['label' => 'Food & drink purchases per month', 'min' => 5000, 'max' => 500000, 'step' => 5000, 'value' => 60000, 'prefix' => 'AED '],
                    ['label' => 'Wasted or unaccounted for', 'min' => 1, 'max' => 20, 'step' => 1, 'value' => 6, 'suffix' => '%'],
                ],
                'result' => 'Food cost you could be losing every year',
                'fix' => 'Recipe-based stock deduction shows what every dish really uses.',
            ],
            'quiz' => [
                'Do kitchen orders get lost or mixed up at rush hour?',
                'Is the real food cost of each dish a guess?',
                'Do you key delivery orders in by hand?',
                'Do you add up the day’s sales and VAT by hand?',
            ],
            'day' => [
                ['09:00', 'Prep list', 'Recipe stock shows what the kitchen needs for today.'],
                ['12:30', 'Lunch rush', 'Orders go straight from the table to the kitchen display.'],
                ['13:10', 'Table 7 splits the bill', 'Split by item or by guest in a few taps.'],
                ['16:00', 'Delivery orders', 'Takeaway and delivery in the same system as dine-in.'],
                ['20:45', 'Busy dinner service', 'Table status and waiting orders at a glance.'],
                ['23:30', 'Closing', 'Day-end report with sales, stock used and VAT.'],
            ],
        ],

        'pharmacy' => [
            'business' => 'Pharmacy',
            'place' => 'pharmacy',
            'modules' => ['inventory', 'vat', 'loyalty', 'online'],
            'calculator' => [
                'tag' => 'Expiry Loss Calculator',
                'title' => 'How much stock expires before it sells?',
                'intro' => 'Expired batches go straight to the bin. Move the sliders to your own numbers.',
                'formula' => 'percent_of_value',
                'sliders' => [
                    ['label' => 'Stock value you hold', 'min' => 20000, 'max' => 3000000, 'step' => 20000, 'value' => 300000, 'prefix' => 'AED '],
                    ['label' => 'Expires before it sells each year', 'min' => 0.5, 'max' => 10, 'step' => 0.5, 'value' => 2, 'suffix' => '%'],
                ],
                'result' => 'Stock you could be losing every year',
                'fix' => 'Batch and expiry alerts tell you what to sell or return first.',
            ],
            'quiz' => [
                'Do you find expired medicine on the shelf?',
                'Is it hard to trace a batch number when a recall comes?',
                'Do you reorder from memory instead of from stock levels?',
                'Do you add up sales or VAT by hand at the end of the day?',
            ],
            'day' => [
                ['08:30', 'Expiry list', 'Batches expiring soon, ready to sell first or return.'],
                ['11:00', 'Prescription sale', 'Linked to the patient record and the right batch.'],
                ['14:00', 'Supplier order', 'Purchase order built from low-stock levels.'],
                ['17:30', 'Recall notice', 'Find every unit of that batch number from one search.'],
                ['19:00', 'Repeat customer', 'Purchase history and loyalty points on file.'],
                ['22:00', 'Day closed', 'Sales, stock and VAT summary in one report.'],
            ],
        ],

        'salon' => [
            'business' => 'Salon / Spa',
            'place' => 'salon',
            'modules' => ['appointments', 'loyalty', 'payroll', 'vat'],
            'calculator' => [
                'tag' => 'No-Show Calculator',
                'title' => 'What do no-shows cost your salon each year?',
                'intro' => 'Every empty chair is lost income. Move the sliders to your own numbers.',
                'formula' => 'noshow',
                'sliders' => [
                    ['label' => 'Appointments per week', 'min' => 10, 'max' => 600, 'step' => 10, 'value' => 120],
                    ['label' => 'No-shows & late cancellations', 'min' => 1, 'max' => 30, 'step' => 1, 'value' => 10, 'suffix' => '%'],
                    ['label' => 'Average ticket', 'min' => 50, 'max' => 2000, 'step' => 10, 'value' => 180, 'prefix' => 'AED '],
                ],
                'result' => 'Income lost to empty chairs every year',
                'fix' => 'Online booking, reminders and deposits help keep the diary full.',
            ],
            'quiz' => [
                'Do clients book by phone or WhatsApp that someone must answer?',
                'Do no-shows leave gaps in your diary every week?',
                'Do you work out stylist commissions by hand?',
                'Do you lose track of products used during services?',
            ],
            'day' => [
                ['09:00', 'Today’s diary', 'Every booking with the stylist and service assigned.'],
                ['10:30', 'Reminders out', 'Tomorrow’s clients reminded of their appointment.'],
                ['13:00', 'A walk-in', 'Slotted into the next free chair straight away.'],
                ['15:30', 'Colour service', 'Products used come off stock automatically.'],
                ['18:00', 'Commission', 'Each stylist’s sales and commission worked out.'],
                ['21:00', 'Day closed', 'Services, product sales and VAT in one report.'],
            ],
        ],

        'gold' => [
            'business' => 'Other',
            'place' => 'jewellery shop',
            'modules' => ['inventory', 'vat', 'loyalty', 'accounting'],
            'calculator' => [
                'tag' => 'Admin Time Calculator',
                'title' => 'What does manual pricing and paperwork cost you?',
                'intro' => 'Re-pricing by hand, writing invoices and reconciling stock all take paid hours. Use your own numbers.',
                'formula' => 'hours',
                'sliders' => [
                    ['label' => 'People doing it', 'min' => 1, 'max' => 20, 'step' => 1, 'value' => 2],
                    ['label' => 'Hours each per week', 'min' => 1, 'max' => 40, 'step' => 1, 'value' => 6],
                    ['label' => 'Cost per hour', 'min' => 20, 'max' => 300, 'step' => 5, 'value' => 60, 'prefix' => 'AED '],
                ],
                'result' => 'Paid hours spent on manual work every year',
                'fix' => 'Gold rate pricing by weight and karat updates every item at once.',
            ],
            'quiz' => [
                'Do you re-price items by hand when the gold rate moves?',
                'Do you write making charges or stone values on invoices manually?',
                'Is it hard to know the exact weight of gold in stock?',
                'Do you add up sales or VAT by hand at the end of the day?',
            ],
            'day' => [
                ['09:30', 'Today’s gold rate', 'Enter it once and every item is priced by weight and karat.'],
                ['11:00', 'A bridal set', 'Making charges and stone value shown clearly on the invoice.'],
                ['13:30', 'Old gold exchange', 'Recorded against the new purchase.'],
                ['16:00', 'Stock check', 'Exact weight and pieces in stock, by karat.'],
                ['19:00', 'Repeat customer', 'Purchase history and loyalty on file.'],
                ['22:00', 'Day closed', 'Sales, stock and VAT summary in one report.'],
            ],
        ],

        'service' => [
            'business' => 'Other',
            'place' => 'business',
            'modules' => ['appointments', 'vat', 'loyalty', 'mobile'],
            'calculator' => [
                'tag' => 'Admin Time Calculator',
                'title' => 'What does paperwork cost your business each year?',
                'intro' => 'Order slips, status calls and adding up the day take paid hours. Use your own numbers.',
                'formula' => 'hours',
                'sliders' => [
                    ['label' => 'People doing it', 'min' => 1, 'max' => 20, 'step' => 1, 'value' => 2],
                    ['label' => 'Hours each per week', 'min' => 1, 'max' => 40, 'step' => 1, 'value' => 5],
                    ['label' => 'Cost per hour', 'min' => 20, 'max' => 300, 'step' => 5, 'value' => 45, 'prefix' => 'AED '],
                ],
                'result' => 'Paid hours spent on manual work every year',
                'fix' => 'Orders, status updates and payments tracked in one system.',
            ],
            'quiz' => [
                'Do you track orders on paper slips or notebooks?',
                'Do customers call to ask if their order is ready?',
                'Do you lose track of advance payments and balances?',
                'Do you add up sales or VAT by hand at the end of the day?',
            ],
            'day' => [
                ['09:00', 'Orders in', 'Each order logged with the customer and a ticket.'],
                ['11:30', 'Advance payment', 'Deposit recorded, balance shown on the order.'],
                ['14:00', 'Work in progress', 'Status updated as each order moves along.'],
                ['17:00', 'Ready for pickup', 'Customer told their order is ready.'],
                ['19:00', 'Collected', 'Balance paid and the order closed.'],
                ['21:00', 'Day closed', 'Sales, balances and VAT in one report.'],
            ],
        ],

        'office' => [
            'business' => 'Other',
            'place' => 'business',
            'modules' => ['accounting', 'vat', 'payroll', 'inventory'],
            'calculator' => [
                'tag' => 'Manual Work Calculator',
                'title' => 'What does manual admin cost you each year?',
                'intro' => 'Invoices, spreadsheets, payroll and reports done by hand. Move the sliders to your own numbers.',
                'formula' => 'hours',
                'sliders' => [
                    ['label' => 'People doing manual admin', 'min' => 1, 'max' => 30, 'step' => 1, 'value' => 3],
                    ['label' => 'Hours each per week', 'min' => 1, 'max' => 40, 'step' => 1, 'value' => 6],
                    ['label' => 'Cost per hour', 'min' => 20, 'max' => 300, 'step' => 5, 'value' => 60, 'prefix' => 'AED '],
                ],
                'result' => 'Paid hours spent on manual work every year',
                'fix' => 'Accounting, VAT, payroll and stock in one system means less re-typing.',
            ],
            'quiz' => [
                'Do you copy the same data between spreadsheets and systems?',
                'Does your VAT return take days to prepare?',
                'Is payroll worked out in a spreadsheet?',
                'Do you wait until month end to see how the business did?',
            ],
            'day' => [
                ['09:00', 'Morning dashboard', 'Yesterday’s sales, receivables and payables at a glance.'],
                ['10:30', 'Invoices out', 'VAT-ready tax invoices created and sent.'],
                ['13:00', 'Supplier bills', 'Purchases recorded once, stock and accounts updated.'],
                ['15:30', 'Payroll', 'Salaries and attendance worked out in the system.'],
                ['17:00', 'Team in the field', 'Sales reps’ orders sync back to the office.'],
                ['18:30', 'Reports', 'Profit, stock and VAT reports ready when you need them.'],
            ],
        ],
    ],

    // Modules offered in the setup builder (key => [label, icon])
    'modules' => [
        'inventory' => ['Inventory & purchasing', 'fas fa-boxes'],
        'vat' => ['Accounting & VAT', 'fas fa-file-invoice-dollar'],
        'loyalty' => ['Loyalty & customers', 'fas fa-heart'],
        'appointments' => ['Appointments', 'far fa-calendar-check'],
        'kitchen' => ['Tables & kitchen display', 'fas fa-utensils'],
        'payroll' => ['Payroll & HR', 'fas fa-user-tie'],
        'online' => ['Online store sync', 'fas fa-globe'],
        'mobile' => ['Van sales & mobile', 'fas fa-truck'],
        'accounting' => ['Full accounting (ERP)', 'fas fa-calculator'],
    ],

    // Problem finder: group => [problem, solution, link]
    'problems' => [
        ['At the counter', 'fas fa-cash-register', [
            ['Queues build up at busy times', 'Fast barcode billing with quick keys', '/features/'],
            ['Customers don’t come back', 'Loyalty points and customer history', '/retail-management/'],
            ['Returns and exchanges take ages', 'Invoice lookup and one-step returns', '/paperless-invoicing/'],
        ]],
        ['Stock & buying', 'fas fa-boxes', [
            ['Stock on the system doesn’t match the shelf', 'Real-time inventory on every sale', '/stock-management/'],
            ['We run out of best-sellers', 'Low-stock alerts and purchase orders', '/stock-management/'],
            ['Stock is spread across branches', 'Multi-branch stock and transfers', '/multi-branch-business-pos-uae/'],
        ]],
        ['Running the business', 'fas fa-chart-line', [
            ['VAT returns take days', 'VAT-ready invoices and FTA reports', '/accounting-software/'],
            ['I can’t see sales when I’m away', 'Live reports on your phone', '/mobile-multitechpos/'],
            ['Staff mistakes and missing cash', 'Staff roles, permissions and shift reports', '/employee-management/'],
        ]],
    ],
];

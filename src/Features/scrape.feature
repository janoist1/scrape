Feature: Scrape Sainsbury’s grocery site command
  The console application​ should scrape the Sainsbury’s grocery site Ripe Fruits page
  and return a JSON array of all the products on the page.

  Scenario: Run tech-test:scrape command
    Given the following html response contents:
      | content      |
      | mock_0.html  |
      | mock_1.html  |
      | mock_2.html  |
      | mock_3.html  |
      | mock_4.html  |
      | mock_5.html  |
      | mock_6.html  |
      | mock_7.html  |
      | mock_8.html  |
      | mock_9.html  |
      | mock_10.html |
      | mock_11.html |
      | mock_12.html |
      | mock_13.html |
      | mock_14.html |
    When I run "tech-test:scrape" command
    Then I should see the following JSON:
      """
        {
           "results":[
              {
                 "description":"Avocados",
                 "size":86.4,
                 "title":"Sainsbury's Avocado Ripe & Ready XL Loose 300g",
                 "unitPrice":"1.50"
              },
              {
                 "description":"Avocados",
                 "size":90.8,
                 "title":"Sainsbury's Avocado, Ripe & Ready x2",
                 "unitPrice":"1.80"
              },
              {
                 "description":"Avocados",
                 "size":86.4,
                 "title":"Sainsbury's Avocados, Ripe & Ready x4",
                 "unitPrice":"3.20"
              },
              {
                 "description":"Conference",
                 "size":86.2,
                 "title":"Sainsbury's Conference Pears, Ripe & Ready x4 (minimum)",
                 "unitPrice":"1.50"
              },
              {
                 "description":"Gold Kiwi",
                 "size":86.3,
                 "title":"Sainsbury's Golden Kiwi x4",
                 "unitPrice":"1.80"
              },
              {
                 "description":"Kiwi",
                 "size":86.7,
                 "title":"Sainsbury's Kiwi Fruit, Ripe & Ready x4",
                 "unitPrice":"1.80"
              },
              {
                 "description":"Kiwi",
                 "size":86.5,
                 "title":"Sainsbury's Kiwi Fruit, SO Organic x4",
                 "unitPrice":"1.00"
              },
              {
                 "description":"by Sainsbury's Ripe and Ready Mango",
                 "size":86.8,
                 "title":"Sainsbury's Mango, Ripe & Ready x2",
                 "unitPrice":"2.25"
              },
              {
                 "description":"Papaya",
                 "size":86.4,
                 "title":"Sainsbury's Papaya, Ripe (each)",
                 "unitPrice":"1.50"
              },
              {
                 "description":"Peach",
                 "size":86.7,
                 "title":"Sainsbury's Peaches Ripe & Ready x4",
                 "unitPrice":"4.00"
              },
              {
                 "description":"Pear",
                 "size":86.2,
                 "title":"Sainsbury's Pears, Ripe & Ready x4 (minimum)",
                 "unitPrice":"1.50"
              },
              {
                 "description":"Plums",
                 "size":86.6,
                 "title":"Sainsbury's Plums Ripe & Ready x5",
                 "unitPrice":"2.50"
              }
           ],
           "total":24.35
        }
      """

# some fail scenarios would look nice here

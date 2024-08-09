# Exchange (recruitment task)

Please write a code sample that meets the following business requirements and:
* Solution modeled in the Domain Driven Design convention
* PHP version 8.*
* Framework-agnostic
* The whole thing has been tested with unit tests

__Recruitment task:__
* Assumptions:
  * The following exchange rates exist:
    * EUR -> GBP 1.5678
    * GBP -> EUR 1.5432
  * The customer is charged a fee of 1% of the amount:
    * Paid to the customer in the event of a sale
    * Collected from the customer in case of purchase
* Cases:
  * Customer sells 100 EUR for GBP
  * Customer buys 100 GBP for EUR
  * Customer sells 100 GBP for EUR
  * Customer buys 100 EUR for GBP

---

__Feedback from employer:__ the team found the solution to be correct, but it seems too simple

Well. Simple solution is not good, because it is too easy to maintain and understand - interesting approach to long term development process.

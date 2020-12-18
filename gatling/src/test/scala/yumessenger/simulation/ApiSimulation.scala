package yumessenger.simulation

import io.gatling.core.Predef._
import io.gatling.core.structure.ScenarioBuilder
import io.gatling.http.Predef._
import io.gatling.http.protocol.HttpProtocolBuilder
import yumessenger.Config
import yumessenger.api._

import scala.util.Random

class ApiSimulation extends Simulation with Config {
  Random.setSeed(System.currentTimeMillis())

  val protocol: HttpProtocolBuilder =
    http
      .baseUrl(BASE_URL)
      .headers(
        Map(
          HttpHeaderNames.ContentType -> HttpHeaderValues.ApplicationJson,
          HttpHeaderNames.UserAgent -> "gatling",
        ),
      )

  val feeder: Iterator[Map[String, Any]] =
    Iterator.continually(
      Map(
        "user_name" -> s"user${Random.nextInt}",
        "user_email" -> s"user${Random.nextInt}@example.com",
        "user_password" -> s"password${Random.nextLong}",
        "message_text" -> s"message${Random.nextLong}",
      ),
    )

  val user: ScenarioBuilder =
    scenario("user simulation")
      .feed(feeder)
      .exec(User.create)
      .exec(Security.login)
      .exec(User.show)
      .repeat(10) {
        exec(
          Message.create,
          Message.list,
          Like.create,
          Like.delete,
          Message.delete,
        )
      }
      .exec(User.delete)

  setUp(
    user
      .inject(
        atOnceUsers(1),
      )
      .protocols(
        protocol,
      )
  )
}

package yumessenger.api

import io.gatling.core.Predef._
import io.gatling.core.structure.ChainBuilder
import io.gatling.http.Predef._

object Security {
  val login: ChainBuilder =
    exec(
      http("security.login")
        .post("/auth")
        .body(
          StringBody(
            """
              {
                "email": "${user_email}",
                "password": "${user_password}"
              }
            """
              .stripMargin
          )
        )
        .check(status.is(201))
        .check(jsonPath("$.session.user.id").is("${user_id}"))
        .check(jsonPath("$.token").saveAs("token"))
    )
}

package yumessenger.api

import io.gatling.core.Predef._
import io.gatling.core.structure.ChainBuilder
import io.gatling.http.Predef._

object User {
  val create: ChainBuilder =
    exec(
      http("user.create")
        .post("/users")
        .body(
          StringBody(
            """
              {
                "nickname": "${user_name}",
                "email": "${user_email}",
                "password": "${user_password}"
              }
            """
              .stripMargin
          )
        )
        .check(status.is(201))
        .check(jsonPath("$.nickname").is("${user_name}"))
        .check(jsonPath("$.email").is("${user_email}"))
        .check(jsonPath("$.createdAt").exists)
        .check(jsonPath("$.password").notExists)
        .check(jsonPath("$.hash").notExists)
        .check(jsonPath("$.id").saveAs("user_id"))
    )

  val show: ChainBuilder =
    exec(
      http("user.show")
        .get("/users/${user_id}")
        .header(HttpHeaderNames.Authorization, "Bearer ${token}")
        .check(status.is(200))
        .check(jsonPath("$.id").is("${user_id}"))
        .check(jsonPath("$.nickname").is("${user_name}"))
        .check(jsonPath("$.email").is("${user_email}"))
    )

  val delete: ChainBuilder =
    exec(
      http("user.delete")
        .delete("/users/${user_id}")
        .header(HttpHeaderNames.Authorization, "Bearer ${token}")
        .check(status.is(204))
    )
}
